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

    // 3. Simpan Data Baru (Diproteksi untuk Admin & Operator)
    public function store(Request $request)
    {
        // 🟢 PROTEKSI HAK AKSES: Tolak eksekusi jika role Kaprodi atau Dosen
        if (in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen'])) {
            return redirect()->route('data-mahasiswa')->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk menambah data.');
        }

        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required',
        ]);

        Mahasiswa::create($request->all());
        return redirect()->route('data-mahasiswa')->with('success', 'Data berhasil ditambahkan!');
    }

    // 4. Detail Mahasiswa (🟢 PERBAIKAN: Method show() yang tadinya hilang sudah ditambahkan kembali)
    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('detail-mahasiswa', compact('mahasiswa'));
    }

    // 5. Edit - Tampil Form (Diproteksi untuk Admin & Operator)
    public function edit($id)
    {
        // 🟢 PROTEKSI HAK AKSES
        if (in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen'])) {
            return redirect()->route('data-mahasiswa')->with('error', 'Akses ditolak! Anda hanya memiliki akses melihat data.');
        }

        $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();
        $lecturers = DB::table('lecturers')->get();
        return view('edit-mahasiswa', compact('mahasiswa', 'lecturers'));
    }

    // 6. Update Data (Diproteksi untuk Admin & Operator)
    public function update(Request $request, $id)
    {
        // 🟢 PROTEKSI HAK AKSES
        if (in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen'])) {
            return redirect()->route('data-mahasiswa')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'nama' => 'required',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($request->all());

        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    // 7. Hapus Data (Diproteksi untuk Admin & Operator)
    public function destroy($id)
    {
        // 🟢 PROTEKSI HAK AKSES
        if (in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen'])) {
            return redirect()->route('data-mahasiswa')->with('error', 'Akses ditolak! Anda tidak diizinkan menghapus data.');
        }

        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect()->route('data-mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    // 8. DASHBOARD UTAMA (Proteksi Tamu)
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $totalMhs = DB::table('mahasiswas')->count();
            $totalLaki = DB::table('mahasiswas')->where('jenis_kelamin', 'Laki-laki')->count();
            $totalPerempuan = DB::table('mahasiswas')->where('jenis_kelamin', 'Perempuan')->count();
            
            $totalDosen = DB::table('lecturers')->count();
            $totalMatkul = DB::table('courses')->count();
            $totalProdi = DB::table('mahasiswas')->whereNotNull('prodi')->distinct('prodi')->count('prodi');

            $prodiData = DB::table('mahasiswas')
                        ->select('prodi', DB::raw('count(*) as total'))
                        ->whereNotNull('prodi')
                        ->groupBy('prodi')->get();

            // Ambil data demografi berdasarkan Tempat Lahir Mahasiswa
            $asalData = DB::table('mahasiswas')
                ->select('tempat_lahir as kota', DB::raw('COUNT(*) as total'))
                ->whereNotNull('tempat_lahir')
                ->groupBy('tempat_lahir')
                ->orderBy('total', 'desc')
                ->get();

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
        if (!Auth::check()) { return redirect()->route('login'); }
        $mahasiswaId = Auth::id(); 

        $rekapAbsen = DB::table('attendances')
            ->join('courses', 'attendances.course_id', '=', 'courses.id')
            ->where('attendances.mahasiswa_id', $mahasiswaId)
            ->select(
                'courses.nama_mk',
                DB::raw('COUNT(attendances.id) as total_pertemuan'),
                DB::raw("SUM(CASE WHEN attendances.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("ROUND((SUM(CASE WHEN attendances.status = 'Hadir' THEN 1 ELSE 0 END) / COUNT(attendances.id)) * 100) as persentase")
            )
            ->groupBy('courses.id', 'courses.nama_mk')
            ->get();

        return view('mahasiswa.presensi', compact('rekapAbsen'));
    }

    // 10. BAHAN & TUGAS
    public function tugas()
    {
        $userAktif = Auth::user();

        $daftarTugas = DB::table('assignments')
            ->leftJoin('submissions', function($join) use ($userAktif) {
                $join->on('assignments.id', '=', 'submissions.assignment_id')
                     ->where('submissions.user_id', '=', $userAktif->id);
            })
            ->select(
                'assignments.*',
                'submissions.id as submission_id',
                'submissions.file_path as file_jawaban',
                'submissions.score as nilai_tugas'
            )
            ->orderBy('assignments.id', 'desc')
            ->get();

        return view('mahasiswa.tugas', compact('daftarTugas'));
    }

    public function uploadTugas(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required',
            'file_tugas'    => 'required|file|mimes:pdf,doc,docx,zip,rar|max:5048',
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $namaFile = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tugas'), $namaFile);

            DB::table('submissions')->updateOrInsert(
                [
                    'assignment_id' => $request->assignment_id,
                    'user_id'       => $user->id
                ],
                [
                    'file_path'  => 'uploads/tugas/' . $namaFile,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );

            return redirect()->back()->with('success', 'Tugas berhasil di-unggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file tugas.');
    }

    public function destroyTugas($id)
    {
        DB::table('assignments')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data bahan/tugas berhasil dihapus!');
    }

    // 11. KRS INDEX
    public function krsIndex()
    {
        if (!Auth::check()) { return redirect()->route('login'); }
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
        if (!Auth::check()) { return redirect()->route('login'); }
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

        $courses = DB::table('courses')->where('status_validasi', 'ACC')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

        return view('schedule.index', compact('schedules', 'courses', 'lecturers', 'rooms'));
    }

    // 14. SIBIMBING INDEX
    public function sibimbingIndex()
    {
        if (!Auth::check()) { return redirect()->route('login'); }
        $user = Auth::user();
        $role = strtolower($user->role);

        if ($role === 'mahasiswa') {
            $logs = DB::table('consultation_logs')
                ->where('mahasiswa_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $logs = DB::table('consultation_logs')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('bimbingan.index', compact('logs'));
    }
    
    // 15. KONSULTASI STORE
    public function sibimbingStore(Request $request)
    {
        if (!Auth::check()) { return redirect()->route('login'); }
        
        $request->validate([
            'jenis_konsultasi' => 'required',
            'dosen_id'         => 'required',
            'topik_bimbingan'  => 'required|string',
            'tanggal_bimbingan'=> 'required|date',
            'catatan_mahasiswa'=> 'required', 
        ]);

        $mahasiswaId = Auth::id();
        $user = Auth::user();
        $topikDanCatatan = $request->topik_bimbingan . " (Catatan: " . $request->catatan_mahasiswa . ")";

        DB::table('consultation_logs')->insert([
            'mahasiswa_id'      => $mahasiswaId,
            'nama_mahasiswa'    => $user->name, 
            'nim'               => $user->nim ?? 'NIM-BELUM-SET', 
            'jenis_konsultasi'  => $request->jenis_konsultasi,
            'lecturer_id'       => $request->dosen_id,
            'room_id'           => 1, 
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan'   => $topikDanCatatan,
            'request_pertemuan' => $request->has('request_pertemuan') ? 'Ya' : 'Tidak',
            'status_bimbingan'  => 'Menunggu Validasi',
            'alasan_penolakan'  => null,
            'nama_ruangan'      => 'Menunggu Konfirmasi',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('mahasiswa.sibimbing')->with('success', 'Konsultasi berhasil diajukan!');
    }
    
    // 16. HALAMAN VALIDASI KURIKULUM (KAPRODI)
    public function validasiKurikulum()
    {
        $courses = DB::table('courses')->orderBy('kode_mk', 'asc')->get();
        return view('kaprodi.kurikulum', compact('courses'));
    }

    // 17. HALAMAN LAPORAN AKADEMIK (KAPRODI)
    public function laporanAkademik()
    {
        $totalMhsProdi = DB::table('mahasiswas')->count();
        $rataIpk = 3.45;
        
        $angkatanData = DB::table('mahasiswas')
            ->select(DB::raw('SUBSTRING(nim, 1, 2) as tahun'), DB::raw('count(*) as total'))
            ->groupBy('tahun')->get();

        return view('kaprodi.laporan', compact('totalMhsProdi', 'rataIpk', 'angkatanData'));
    }

    // 18. UPDATE STATUS SIBIMBING
    public function sibimbingUpdateStatus(Request $request, $id, $status)
    {
        $updateData = [
            'status_bimbingan' => $status,
            'updated_at' => now()
        ];

        if ($status === 'Ditolak') {
            $updateData['alasan_penolakan'] = $request->input('alasan_penolakan');
        }

        DB::table('consultation_logs')
            ->where('id', $id)
            ->update($updateData);

        return redirect()->back()->with('success', 'Status bimbingan mahasiswa berhasil diperbarui!');
    }
}