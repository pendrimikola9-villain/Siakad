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
    public function presensi()
    {
        $mahasiswaId = Auth::id() ?? 1; 

        $rekapAbsen = DB::table('attendances')
            ->join('courses', 'attendances.course_id', '=', 'courses.id')
            ->select(
                'attendances.course_id',
                'courses.nama_mk', 
                DB::raw('COUNT(attendances.id) as total_pertemuan'),
                DB::raw("SUM(CASE WHEN attendances.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir")
            )
            ->where('attendances.mahasiswa_id', $mahasiswaId)
            ->groupBy('attendances.course_id', 'courses.nama_mk')
            ->get();

        foreach ($rekapAbsen as $item) {
            $item->persentase = $item->total_pertemuan > 0 
                ? round(($item->total_hadir / $item->total_pertemuan) * 100) 
                : 0;

            $item->layak_ujian = $item->persentase >= 80;
        }

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
    $mahasiswaId = Auth::id();
    
    // UBAH NAMA VARIABEL MENJADI $schedules AGAR MATCH DENGAN BLADE
    $schedules = DB::table('class_schedules')
        ->join('courses', 'class_schedules.course_id', '=', 'courses.id')
        ->select('class_schedules.*', 'courses.nama_mk', 'courses.sks', 'courses.kode_mk')
        ->get();

    return view('schedule.index', compact('schedules'));
}

  // 14. SIBIMBING INDEX (Sudah ada di file kamu)
    public function sibimbingIndex()
    {
        $mahasiswaId = Auth::id();

        $logs = DB::table('consultation_logs')
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bimbingan.index', compact('logs'));
    }

    // 🟢 15. FITUR BARU: SIMPAN KONSULTASI / JANJI TEMU GLOBAL SIBIMBING
    public function sibimbingStore(Request $request)
    {
        // Validasi input data dari form bimbingan mahasiswa
        $request->validate([
            'jenis_konsultasi' => 'required',
            'dosen_id'         => 'required',
            'topik_bimbingan'  => 'required|string|max:255',
            'tanggal_bimbingan'=> 'required|date',
            'catatan_mahasiswa'=> 'required',
        ]);

        $mahasiswaId = Auth::id();

        // Ambil data user yang sedang login untuk mengisi nama & NIM mahasiswa secara otomatis
        $user = Auth::user();

        // Ambil nama dosen dari tabel lecturers berdasarkan id yang dipilih
        $dosen = DB::table('lecturers')->where('id', $request->dosen_id)->first();
        $namaDosen = $dosen ? $dosen->nama_dosen : 'Dosen Tidak Diketahui';

        // Insert data ke dalam tabel consultation_logs
        DB::table('consultation_logs')->insert([
            'mahasiswa_id'      => $mahasiswaId,
            'nama_mahasiswa'    => $user->name, // Mengambil dari user yang login
            'nim'               => $user->nim ?? 'NIM-BELUM-SET', // Mengambil NIM dari profile user jika ada
            'dosen_id'          => $request->dosen_id,
            'nama_dosen'        => $namaDosen,
            'jenis_konsultasi'  => $request->jenis_konsultasi,
            'topik_bimbingan'   => $request->topik_bimbingan,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'request_pertemuan' => $request->has('request_pertemuan') ? 'Ya' : 'Tidak', // Cek checkbox janji temu
            'catatan_mahasiswa' => $request->catatan_mahasiswa,
            'status_bimbingan'  => 'Menunggu Validasi', // Status default awal terkunci
            'nama_ruangan'      => 'Menunggu Konfirmasi', // Default sebelum di-set dosen
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('mahasiswa.sibimbing')->with('success', 'Konsultasi berhasil diajukan! Menunggu tanggapan Dosen/Kaprodi.');
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

}