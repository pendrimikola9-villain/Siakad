<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->input('student_id', Auth::id());
        $userAktif = Auth::user();
        
        // Tangkap filter semester dari dropdown
        $filterSemester = $request->input('filter_semester');

        // ==========================================
        // TAHAP 1: FINANSIAL GATEWAY (DATABASE REAL)
        // ==========================================
        $mhsDb = DB::table('users')->where('id', $studentId)->first();
        
        // Prioritaskan status dari database, jika null baru cek session / default
        $statusBayar = $mhsDb->status_bayar ?? session("status_bayar_{$studentId}", 'Belum Bayar'); 
        $fileBukti = session("file_bukti_{$studentId}", 'bukti_transfer_siakad.png');

        // Aksi 1: Mahasiswa melakukan upload bukti bayar
        if ($request->has('aksi_bayar') && $request->aksi_bayar === 'upload') {
            DB::table('users')->where('id', $studentId)->update(['status_bayar' => 'Menunggu Validasi']);
            session(["status_bayar_{$studentId}" => 'Menunggu Validasi']);
            return redirect()->back()->with('success', 'Bukti pembayaran berhasil di-unggah! Menunggu verifikasi dari Admin.');
        }

        // Aksi 2: Mahasiswa menghapus / membatalkan bukti bayar
        if ($request->has('aksi_bayar') && $request->aksi_bayar === 'hapus_bukti') {
            DB::table('users')->where('id', $studentId)->update(['status_bayar' => 'Belum Bayar']);
            session()->forget(["status_bayar_{$studentId}", "file_bukti_{$studentId}"]);
            return redirect()->back()->with('success', 'Bukti pembayaran berhasil dihapus.');
        }

        // Aksi 3: Admin mengonfirmasi status pembayaran menjadi LUNAS
        if ($request->has('aksi_bayar') && $request->aksi_bayar === 'luluskan_admin') {
            // 🟢 UPDATE PERMANEN LANGSUNG KE TABEL USERS DATABASE
            DB::table('users')->where('id', $studentId)->update(['status_bayar' => 'Lunas']);
            session(["status_bayar_{$studentId}" => 'Lunas']);
            
            return redirect()->back()->with('success', 'Pembayaran mahasiswa berhasil divalidasi menjadi LUNAS!');
        }

        // ==========================================
        // TAHAP 2: ENGINE LOGIKA FUZZY (IPK & PRESENSI)
        // ==========================================
        $ipk = 3.45; 
        $totalHadir = DB::table('attendances')->where('mahasiswa_id', $studentId)->where('status', 'Hadir')->count();
        $totalAbsen = DB::table('attendances')->where('mahasiswa_id', $studentId)->count();
        $persenHadir = $totalAbsen > 0 ? round(($totalHadir / $totalAbsen) * 100) : 100;

        $jatahSksMaksimal = $this->hitungFuzzySks($ipk, $persenHadir);

        // ==========================================
        // TAHAP 3: FILTER KATALOG MATKUL & REKOMENDASI
        // ==========================================
        $queryMatkul = DB::table('courses');
        
        if (!empty($filterSemester)) {
            $queryMatkul->where('semester', $filterSemester);
        }
        
        $katalogMatkul = $queryMatkul->orderBy('semester', 'asc')->get();
        
        $matkulBurukIds = DB::table('attendances')
            ->where('mahasiswa_id', $studentId)
            ->select('course_id', DB::raw('SUM(case when status="Hadir" then 1 else 0 end) / COUNT(*) * 100 as rate'))
            ->groupBy('course_id')
            ->having('rate', '<', 80)
            ->pluck('course_id')
            ->toArray();

        foreach ($katalogMatkul as $mk) {
            if (in_array($mk->id, $matkulBurukIds)) {
                $mk->rekomendasi_status = 'Wajib Mengulang';
                $mk->rekomendasi_badge = 'bg-danger';
                $mk->alasan = 'Persentase kehadiran semester lalu di bawah 80%';
            } elseif ($jatahSksMaksimal == 24 && $mk->semester > 4) {
                $mk->rekomendasi_status = 'Paket Akselerasi';
                $mk->rekomendasi_badge = 'bg-warning text-dark';
                $mk->alasan = 'Fuzzy mendeteksi Anda berhak mengambil kelas tingkat atas lebih cepat';
            } else {
                $mk->rekomendasi_status = 'Reguler Berjalan';
                $mk->rekomendasi_badge = 'bg-light text-secondary';
                $mk->alasan = 'Sesuai dengan alur kurikulum semester berjalan';
            }
        }

        $krsDiambil = DB::table('krs')->where('user_id', $studentId)->pluck('course_id')->toArray();
        $totalSksDipilih = DB::table('krs')->join('courses', 'krs.course_id', '=', 'courses.id')->where('user_id', $studentId)->sum('courses.sks');

        // JIKA LOGIN BUKAN MAHASISWA (Dosen/Admin)
        $daftarPengajuanKrs = [];
        if (Auth::user()?->role !== 'mahasiswa') {
            $daftarPengajuanKrs = DB::table('users')
                ->join('krs', 'users.id', '=', 'krs.user_id')
                ->join('courses', 'krs.course_id', '=', 'courses.id')
                ->where('users.role', 'mahasiswa')
                ->select(
                    'krs.user_id as id_mahasiswa', 
                    'users.name',
                    'users.status_bayar', // 🟢 Tarik kolom status_bayar dari DB
                    'krs.kelas',    
                    'krs.angkatan', 
                    DB::raw("MIN(krs.status) as status_krs"),
                    DB::raw('SUM(courses.sks) as total_sks')
                )
                ->groupBy('krs.user_id', 'users.name', 'users.status_bayar', 'krs.kelas', 'krs.angkatan')
                ->get();
                
            foreach($daftarPengajuanKrs as $p) {
                // 🟢 Ambil status finansial riil dari database
                $p->finansial_status = $p->status_bayar ?? session("status_bayar_{$p->id_mahasiswa}", 'Menunggu Validasi');
                $p->file_bukti = 'bukti_transfer_siakad.png';
            }
        }

        return view('mahasiswa.krs', compact(
            'katalogMatkul', 
            'krsDiambil', 
            'daftarPengajuanKrs', 
            'jatahSksMaksimal', 
            'totalSksDipilih',
            'ipk',
            'persenHadir',
            'statusBayar',
            'fileBukti',
            'filterSemester'
        ));
    }

    private function hitungFuzzySks($ipk, $persenHadir)
    {
        $ipkKurang = $ipk < 2.75 ? 1 : ($ipk < 3.00 ? (3.00 - $ipk) / (3.00 - 2.75) : 0);
        $ipkCukup  = ($ipk >= 2.75 && $ipk <= 3.25) ? 1 : 0;
        $ipkuTinggi = $ipk > 3.25 ? 1 : ($ipk >= 3.00 ? ($ipk - 3.00) / (3.25 - 3.00) : 0);

        $absenJarang  = $persenHadir < 80 ? 1 : ($persenHadir < 85 ? (85 - $persenHadir) / (85 - 80) : 0);
        $absenStandar = ($persenHadir >= 80 && $persenHadir <= 90) ? 1 : 0;
        $absenRajin   = $persenHadir > 90 ? 1 : ($persenHadir >= 85 ? ($persenHadir - 85) / (90 - 85) : 0);

        $outputSks = 18;

        if ($ipkuTinggi > 0 && $absenRajin > 0) {
            $outputSks = 24;
        }
        elseif ($ipkCukup > 0 && ($absenStandar > 0 || $absenRajin > 0)) {
            $outputSks = 21;
        }
        elseif ($ipkuTinggi > 0 && $absenJarang > 0) {
            $outputSks = 21;
        }
        
        if ($ipkKurang > 0) {
            $outputSks = 18;
        }

        return $outputSks;
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'matkul' => 'required|array',
            'student_id' => 'required',
            'max_sks' => 'required|integer'
        ]);

        $studentId = $request->student_id;
        $maxSks = $request->max_sks;

        $totalSksDiajukan = DB::table('courses')
            ->whereIn('id', $request->matkul)
            ->sum('sks');

        if ($totalSksDiajukan > $maxSks) {
            return redirect()->back()->with('error', "Pengajuan KRS Gagal! Total SKS yang Anda pilih ({$totalSksDiajukan} SKS) melebihi batas rekomendasi cerdas Fuzzy ({$maxSks} SKS).");
        }

        DB::transaction(function () use ($request, $studentId) {
            DB::table('krs')->where('user_id', $studentId)->delete();

            foreach ($request->matkul as $courseId) {
                DB::table('krs')->insert([
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'status' => 'Pending',
                    'kelas' => '41 TI',      
                    'angkatan' => '2024',    
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->back()->with('success', 'Rencana studi (KRS) Anda berhasil diajukan! Menunggu validasi Dosen PA.');
    }

    public function approve($id)
    {
        DB::table('krs')->where('user_id', $id)->update([
            'status' => 'Disetujui',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'KRS Mahasiswa bersangkutan berhasil disetujui!');
    }

    /**
     * Halaman Khusus Evaluasi Fuzzy Belajar (UAS Pemrograman Web 2)
     */
    public function fuzzyEvaluasi(Request $request)
    {
        $studentId = $request->input('student_id', Auth::id());

        // 1. Ambil Indikator Input Manajemen Belajar Riil
        $totalHadir = DB::table('attendances')->where('mahasiswa_id', $studentId)->where('status', 'Hadir')->count();
        $totalAbsen = DB::table('attendances')->where('mahasiswa_id', $studentId)->count();
        
        $persenHadir = $totalAbsen > 0 ? round(($totalHadir / $totalAbsen) * 100) : 92;
        $nilaiTugas  = 88.50; // Input Nilai Tugas
        $keaktifan   = 80;    // Input Keaktifan Diskusi (Poin)

        // 2. Hitung Engine Fuzzy
        $fuzzyData = $this->hitungFuzzySks($persenHadir, $nilaiTugas, $keaktifan);
        $jatahSksMaksimal = is_array($fuzzyData) ? $fuzzyData['sks_crisp'] : $fuzzyData;
        $kategori = is_array($fuzzyData) ? $fuzzyData['kategori'] : 'Reguler';

        // 3. Simpan Ke Database (Jika Tabel Model FuzzyResult Sudah Ada)
        if (class_exists('App\Models\FuzzyResult')) {
            \App\Models\FuzzyResult::updateOrCreate(
                ['user_id' => $studentId],
                [
                    'kehadiran_input'     => $persenHadir,
                    'tugas_input'         => $nilaiTugas,
                    'keaktifan_input'     => $keaktifan,
                    'hasil_sks_crisp'     => $jatahSksMaksimal,
                    'kategori_rekomendasi'=> $kategori
                ]
            );
        }

        // 🟢 KODE BARU (Hanya menampilkan Mahasiswa):
$riwayatFuzzy = DB::table('fuzzy_results')
    ->join('users', 'fuzzy_results.user_id', '=', 'users.id')
    ->where('users.role', 'mahasiswa') // <-- Memfilter agar dosen/admin tidak ikut masuk
    ->select('fuzzy_results.*', 'users.name')
    ->orderBy('fuzzy_results.updated_at', 'desc')
    ->get();

        return view('mahasiswa.fuzzy_evaluasi', compact(
            'persenHadir', 'nilaiTugas', 'keaktifan', 'jatahSksMaksimal', 'kategori', 'riwayatFuzzy'
        ));
    }
}