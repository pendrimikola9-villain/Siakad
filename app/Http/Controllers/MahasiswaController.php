<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    // 1. Menampilkan Tabel (Halaman Output Admin)
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('tampil-mahasiswa', compact('mahasiswa'));
    }

    // Menampilkan Transaksi Gabungan Nilai
    public function tampilkanTransaksi()
    {
        $dataNilai = DB::table('grades')
            ->join('mahasiswas', 'grades.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('courses', 'grades.course_id', '=', 'courses.id')
            ->select(
                'mahasiswas.nama',
                'courses.nama_mk',
                'courses.sks',
                'grades.nilai'
            )
            ->get();

        return view('tampil-nilai', compact('dataNilai'));
    }

    // 2. Menampilkan Form Tambah Mahasiswa
    public function create()
    {
        $lecturers = DB::table('lecturers')->get();
        return view('Data-mahasiswa', compact('lecturers'));
    }

    // 3. Simpan Data Baru
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required',
        ]);

        Mahasiswa::create($request->all());
        return redirect()->route('data-mahasiswa')->with('success', 'Data berhasil ditambahkan!');
    }

    // 4. Detail Mahasiswa
    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('detail-mahasiswa', compact('mahasiswa'));
    }

    // 5. Edit (Tampil Form)
    public function edit($id)
    {
        $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();
        $lecturers = DB::table('lecturers')->get();
        return view('edit-mahasiswa', compact('mahasiswa', 'lecturers'));
    }

    // 6. Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($request->all());

        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    // 7. Hapus Data
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    // 8. DASHBOARD UTAMA
    // 8. DASHBOARD UTAMA (Mendukung Grafik Live Khusus Operator)
    public function dashboard()
    {
        try {
            // Data Standar Summary Card
            $totalMhs = DB::table('mahasiswas')->count();
            $totalLaki = DB::table('mahasiswas')->where('jenis_kelamin', 'Laki-laki')->count();
            $totalPerempuan = DB::table('mahasiswas')->where('jenis_kelamin', 'Perempuan')->count();
            
            // Tambahan summary untuk Operator Fakultas
            $totalDosen = DB::table('lecturers')->count();
            $totalMatkul = DB::table('courses')->count();
            $totalProdi = DB::table('mahasiswas')->whereNotNull('prodi')->distinct('prodi')->count('prodi');

            // Data untuk Grafik Batang (Mahasiswa per Prodi)
            $prodiData = DB::table('mahasiswas')
                        ->select('prodi', DB::raw('count(*) as total'))
                        ->whereNotNull('prodi')
                        ->groupBy('prodi')->get();

            // Data untuk Grafik Lingkaran (Sebaran Asal Kota Mahasiswa)
            $asalData = DB::table('mahasiswas')
                        ->select('alamat as kota', DB::raw('count(*) as total'))
                        ->whereNotNull('alamat')
                        ->groupBy('alamat')->get();

            // Kirim semua variabel ke view dashboard
            return view('dashboard', compact(
                'totalMhs', 'totalLaki', 'totalPerempuan', 
                'totalDosen', 'totalMatkul', 'totalProdi',
                'prodiData', 'asalData'
            ));
            
        } catch (\Exception $e) {
            return "Terjadi Error di Controller: " . $e->getMessage();
        }
    }

    // 9. PRESENSI MAHASISWA
   public function rekapPresensi()
    {
        $mahasiswaId = Auth::id(); // Mengambil ID Mahasiswa yang sedang login

        // Kueri menghitung rekap absensi per mata kuliah secara dinamis
        $rekapAbsen = DB::table('attendances')
            ->join('courses', 'attendances.course_id', '=', 'courses.id')
            ->where('attendances.mahasiswa_id', $mahasiswaId)
            ->select(
                'courses.nama_mk',
                // Hitung total baris absen sebagai jumlah pertemuan perkuliahan
                DB::raw('COUNT(attendances.id) as total_pertemuan'),
                // Hitung berapa kali mahasiswa berstatus 'Hadir'
                DB::raw("SUM(CASE WHEN attendances.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
                // Hitung persentase kehadiran: (Hadir / Total Pertemuan) * 100
                DB::raw("ROUND((SUM(CASE WHEN attendances.status = 'Hadir' THEN 1 ELSE 0 END) / COUNT(attendances.id)) * 100) as persentase")
            )
            ->groupBy('courses.id', 'courses.nama_mk')
            ->get();

        return view('mahasiswa.presensi', compact('rekapAbsen'));
    }


    // 10. BAHAN & TUGAS
    public function tugas()
    {
        try {
            $daftarTugas = DB::table('assignments')->orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $daftarTugas = [];
        }
        
        return view('mahasiswa.tugas', compact('daftarTugas'));
    }

    // 11. KRS INDEX
    public function krsIndex()
    {
        $mahasiswaId = Auth::id();
        $katalogMatkul = DB::table('courses')->get();
        $krsDiambil = DB::table('study_plans')
            ->where('mahasiswa_id', $mahasiswaId)
            ->pluck('course_id')
            ->toArray();

        return view('mahasiswa.krs', compact('katalogMatkul', 'krsDiambil'));
    }

    // 12. KRS SIMPAN
    public function krsSimpan(Request $request)
    {
        $mahasiswaId = Auth::id();
        $semesterAktif = "2025/2026 Genap";
        $matkulDipilih = $request->input('matkul', []);

        DB::table('study_plans')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('semester_akademik', $semesterAktif)
            ->delete();

        foreach ($matkulDipilih as $courseId) {
            DB::table('study_plans')->insert([
                'mahasiswa_id' => $mahasiswaId,
                'course_id' => $courseId,
                'semester_akademik' => $semesterAktif,
                'status' => 'Menunggu Verifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('mahasiswa.krs')->with('success', 'KRS Berhasil disimpan dan diajukan ke Dosen Wali!');
    }

    // 13. SIPLAR (JADWAL KULIAH)
  public function siplarIndex()
    {
        // 1. Query ambil data list jadwal utama
        $schedules = DB::table('class_schedules')
            ->join('courses', 'class_schedules.course_id', '=', 'courses.id')
            ->join('lecturers', 'class_schedules.lecturer_id', '=', 'lecturers.id')
            ->join('rooms', 'class_schedules.room_id', '=', 'rooms.id')
            ->select(
                'class_schedules.*',
                'courses.kode_mk',
                'courses.nama_mk',
                'courses.sks',
                'lecturers.nama_dosen',
                'rooms.nama_ruangan'
            )
            ->get();

        // 💡 PERBAIKAN: Tarik data pembantu untuk dropdown modal form agar tidak Undefined
        $courses = DB::table('courses')->where('status_validasi', 'ACC')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

        // 2. Kirim semua variabel ke dalam view schedule.index kamu
        return view('schedule.index', compact('schedules', 'courses', 'lecturers', 'rooms'));
    }

  // 14. SIBIMBING INDEX (Sudah ada di file kamu)
  public function sibimbingIndex()
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'mahasiswa') {
            // Kalau mahasiswa login, dia hanya melihat riwayat bimbingannya sendiri
            $logs = DB::table('consultation_logs')
                ->where('mahasiswa_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // 🟢 BYPASS TUGAS KULIAH: Dosen, Kaprodi, atau Admin bisa melihat SEMUA data bimbingan mahasiswa
            $logs = DB::table('consultation_logs')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('bimbingan.index', compact('logs'));
    }
    
    // 🟢 15. FITUR BARU: SIMPAN KONSULTASI / JANJI TEMU GLOBAL SIBIMBING
  // 🟢 15. FITUR BARU: SIMPAN KONSULTASI / JANJI TEMU GLOBAL SIBIMBING
    public function sibimbingStore(Request $request)
    {
        // Validasi input data dari form bimbingan mahasiswa
        $request->validate([
            'jenis_konsultasi' => 'required',
            'dosen_id'         => 'required',
            'topik_bimbingan'  => 'required|string',
            'tanggal_bimbingan'=> 'required|date',
            // Catatan mahasiswa opsional karena tidak ada kolomnya di tabel, bisa kita gabung ke topik bimbingan jika perlu
            'catatan_mahasiswa'=> 'required', 
        ]);

        $mahasiswaId = Auth::id();
        $user = Auth::user();

        // Menyusun pesan gabungan topik dan catatan agar input dari form mahasiswa tidak hilang
        $topikDanCatatan = $request->topik_bimbingan . " (Catatan: " . $request->catatan_mahasiswa . ")";

        // Insert data ke dalam tabel consultation_logs sesuai struktur riil HeidiSQL
        DB::table('consultation_logs')->insert([
            'mahasiswa_id'      => $mahasiswaId,
            'nama_mahasiswa'    => $user->name, 
            'nim'               => $user->nim ?? 'NIM-BELUM-SET', 
            'jenis_konsultasi'  => $request->jenis_konsultasi,
            'lecturer_id'       => $request->dosen_id, // 🟢 Sesuai kolom ke-6
            
            // 🟢 KUNCI UTAMA: Kolom room_id wajib diisi (NOT NULL). Kita set default value 1 
            // agar lolos validasi database sebelum nantinya diubah oleh dosen/admin.
            'room_id'           => 1, 
            
            'tanggal_bimbingan' => $request->tanggal_bimbingan, // 🟢 Sesuai kolom ke-8
            'topik_bimbingan'   => $topikDanCatatan, // 🟢 Sesuai kolom ke-9
            'request_pertemuan' => $request->has('request_pertemuan') ? 'Ya' : 'Tidak', // 🟢 Sesuai kolom ke-10
            'status_bimbingan'  => 'Menunggu Validasi', // 🟢 Sesuai kolom ke-11
            'alasan_penolakan'  => null, // 🟢 Sesuai kolom ke-12
            'nama_ruangan'      => 'Menunggu Konfirmasi', // 🟢 Sesuai kolom ke-13
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('mahasiswa.sibimbing')->with('success', 'Konsultasi berhasil diajukan! Data sukses terekam ke database.');
    }
    
    // 16. HALAMAN VALIDASI KURIKULUM (KAPRODI)
    public function validasiKurikulum()
    {
        // Mengambil semua mata kuliah untuk divalidasi statusnya oleh Kaprodi
        $courses = DB::table('courses')->orderBy('kode_mk', 'asc')->get();
        return view('kaprodi.kurikulum', compact('courses'));
    }

    // 17. HALAMAN LAPORAN AKADEMIK (KAPRODI)
    public function laporanAkademik()
    {
        // Mengambil data ringkasan untuk laporan prodi
        $totalMhsProdi = DB::table('mahasiswas')->count();
        $rataIpk = 3.45; // Nilai dummy standar rata-rata prodi
        
        // Sebaran per angkatan
        $angkatanData = DB::table('mahasiswas')
            ->select(DB::raw('SUBSTRING(nim, 1, 2) as tahun'), DB::raw('count(*) as total'))
            ->groupBy('tahun')->get();

        return view('kaprodi.laporan', compact('totalMhsProdi', 'rataIpk', 'angkatanData'));
    }

    // 💾 FUNGSI: Update Status ACC / Tolak Bimbingan Mahasiswa
    public function sibimbingUpdateStatus(Request $request, $id, $status)
    {
        $updateData = [
            'status_bimbingan' => $status,
            'updated_at' => now()
        ];

        // Jika ditolak, simpan alasan penolakannya ke kolom alasan_penolakan sesuai struktur HeidiSQL
        if ($status === 'Ditolak') {
            $updateData['alasan_penolakan'] = $request->input('alasan_penolakan');
        }

        DB::table('consultation_logs')
            ->where('id', $id)
            ->update($updateData);

        return redirect()->back()->with('success', 'Status bimbingan mahasiswa berhasil diperbarui!');
    }

}