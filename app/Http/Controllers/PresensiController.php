<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index()
    {
        // 1. Ambil data mata kuliah dasar
        $rekapAbsen = DB::table('courses')
            ->select('id as course_id', 'nama_mk')
            ->get();

        // 2. Hitung statistik riil per mata kuliah langsung dari tabel attendances
        foreach ($rekapAbsen as $ra) {
            $ra->total_pertemuan = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->distinct('tanggal')
                ->count('tanggal');

            $ra->total_hadir = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->where('status', 'Hadir')
                ->count();

            $totalRecordMatkul = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->count();

            if ($totalRecordMatkul > 0 && $ra->total_pertemuan > 0) {
                $ra->persentase = round(($ra->total_hadir / $totalRecordMatkul) * 100);
            } else {
                $ra->persentase = 0;
            }
        }

        $daftarAngkatan = ['2022', '2023', '2024', '2025', '2026'];

        return view('mahasiswa.presensi', compact('rekapAbsen', 'daftarAngkatan')); 
    }

  public function getMahasiswaByFilter(Request $request)
{
    $courseId = $request->course_id;
    $tanggalInput = $request->tanggal ?? date('Y-m-d');
    $userAktif = Auth::user(); // Mendeteksi siapa yang sedang login

    // 🟢 HAK AKSES QUERY: Jika Mahasiswa, kunci hanya untuk ID dia sendiri
    if ($userAktif->role === 'mahasiswa') {
        $mahasiswaKrs = DB::table('krs')
            ->join('users', 'krs.user_id', '=', 'users.id')
            ->where('krs.course_id', $courseId)
            ->where('users.id', $userAktif->id) // <--- Kunci pengunci hak akses mahasiswa
            ->select('users.id', 'users.name', 'users.email as npm')
            ->get();
    } else {
        // Jika Dosen / Kaprodi / Operator / Admin, tarik semua mahasiswa kelas
        $mahasiswaKrs = DB::table('krs')
            ->join('users', 'krs.user_id', '=', 'users.id')
            ->where('krs.course_id', $courseId)
            ->select('users.id', 'users.name', 'users.email as npm')
            ->orderBy('users.name', 'asc')
            ->get();
    }

    $dataHasil = [];

    foreach ($mahasiswaKrs as $mhs) {
        // Hitung total akumulatif riwayat kehadiran
        $totalHadir = DB::table('attendances')->where(['course_id' => $courseId, 'mahasiswa_id' => $mhs->id, 'status' => 'Hadir'])->count();
        $totalSakit = DB::table('attendances')->where(['course_id' => $courseId, 'mahasiswa_id' => $mhs->id, 'status' => 'Sakit'])->count();
        $totalIzin  = DB::table('attendances')->where(['course_id' => $courseId, 'mahasiswa_id' => $mhs->id, 'status' => 'Izin'])->count();
        $totalAlfa  = DB::table('attendances')->where(['course_id' => $courseId, 'mahasiswa_id' => $mhs->id, 'status' => 'Alfa'])->count();
        
        $totalPertemuan = $totalHadir + $totalSakit + $totalIzin + $totalAlfa;
        $persentase = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 0;

        // Cek status absensi hari ini
        $statusHariIni = DB::table('attendances')
            ->where(['course_id' => $courseId, 'mahasiswa_id' => $mhs->id, 'tanggal' => $tanggalInput])
            ->value('status');

        $dataHasil[] = [
            'id' => $mhs->id,
            'name' => $mhs->name,
            'npm' => $mhs->npm,
            'hadir' => $totalHadir,
            'sakit' => $totalSakit,
            'izin' => $totalIzin,
            'alfa' => $totalAlfa,
            'total_pertemuan' => $totalPertemuan,
            'persentase' => $persentase,
            'status_hari_ini' => $statusHariIni ?: 'Belum Absen'
        ];
    }

    return response()->json([
        'mahasiswa' => $dataHasil,
        'course_id' => $courseId,
        'tanggal' => $tanggalInput
    ]);
}

   public function storeMassal(Request $request)
{
    $request->validate([
        'course_id' => 'required',
        'tanggal' => 'required|date',
    ]);

    $courseId = $request->course_id;
    $tanggal = $request->tanggal;

    // Ambil semua ID mahasiswa yang mengontrak matkul ini
    $mahasiswaUserIds = DB::table('krs')
        ->where('course_id', $courseId)
        ->pluck('user_id') 
        ->toArray();

    if (empty($mahasiswaUserIds)) {
         return redirect()->back()->with('error', 'Tidak ada mahasiswa di kelas ini.');
    }

    // Variabel untuk menghitung rangkuman (summary) absensi hari ini
    $hitungHadir = 0;
    $hitungSakit = 0;
    $hitungIzin  = 0;
    $hitungAlfa  = 0;

    DB::transaction(function () use ($request, $courseId, $tanggal, $mahasiswaUserIds, &$hitungHadir, &$hitungSakit, &$hitungIzin, &$hitungAlfa) {
        // 1. Hapus data absensi lama di tanggal dan matkul ini agar tidak duplikat
        DB::table('attendances')
            ->where('course_id', $courseId)
            ->where('tanggal', $tanggal)
            ->delete();

        // 2. Masukkan data absensi yang baru sesuai pilihan di website
        foreach ($mahasiswaUserIds as $userId) {
            // Mengambil status dari form array status[user_id]
            $statusKehadiran = $request->input("status.{$userId}", 'Hadir');

            // Kalkulasi summary berdasarkan status yang dipilih dosen
            if ($statusKehadiran === 'Hadir') $hitungHadir++;
            elseif ($statusKehadiran === 'Sakit') $hitungSakit++;
            elseif ($statusKehadiran === 'Izin') $hitungIzin++;
            elseif ($statusKehadiran === 'Alfa') $hitungAlfa++;

            DB::table('attendances')->insert([
                'course_id' => $courseId,
                'mahasiswa_id' => $userId, 
                'tanggal' => $tanggal,
                'status' => $statusKehadiran,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    });

    // 🟢 Rangkai kalimat alert dinamis sesuai hasil hitungan di atas
    $pesanSukses = "Presensi kelas berhasil disimpan! Rangking hari ini: " . 
                   "Hadir: {$hitungHadir} orang, " . 
                   "Sakit: {$hitungSakit} orang, " . 
                   "Izin: {$hitungIzin} orang, " . 
                   "Alfa: {$hitungAlfa} orang.";

    return redirect()->back()->with('success', $pesanSukses);
}
}