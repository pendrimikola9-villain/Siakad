<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $katalogMatkul = DB::table('courses')->orderBy('semester', 'asc')->get();
        $studentId = $request->input('student_id', Auth::id());

        // Ambil data id matkul yang sudah pernah diambil mahasiswa ini
        $krsDiambil = DB::table('krs')
            ->where('user_id', $studentId)
            ->pluck('course_id')
            ->toArray();

        // JIKA LOGIN BUKAN MAHASISWA (Dosen/Admin): Ambil daftar pengajuan KRS mahasiswa
      // JIKA LOGIN BUKAN MAHASISWA (Dosen/Admin): Ambil daftar pengajuan KRS mahasiswa
      // JIKA LOGIN BUKAN MAHASISWA (Dosen/Admin): Ambil daftar pengajuan KRS mahasiswa
        $daftarPengajuanKrs = [];
        if (Auth::user()?->role !== 'mahasiswa') {
            $daftarPengajuanKrs = DB::table('users')
                ->join('krs', 'users.id', '=', 'krs.user_id')
                ->join('courses', 'krs.course_id', '=', 'courses.id')
                ->where('users.role', 'mahasiswa')
                ->select(
                    'users.id',
                    'users.name',
                    'krs.kelas',    // 🟢 Ambil data kelas dari tabel krs
                    'krs.angkatan', // 🟢 Ambil data angkatan dari tabel krs
                    DB::raw("MIN(krs.status) as status_krs"),
                    DB::raw('SUM(courses.sks) as total_sks')
                )
                ->groupBy('users.id', 'users.name', 'krs.kelas', 'krs.angkatan')
                ->get();
        }

        return view('mahasiswa.krs', compact('katalogMatkul', 'krsDiambil', 'daftarPengajuanKrs'));
    }
public function simpan(Request $request)
    {
        $request->validate([
            'matkul' => 'required|array',
            'student_id' => 'required'
        ]);

        $studentId = $request->student_id;

        DB::transaction(function () use ($request, $studentId) {
            // Bersihkan pilihan KRS lama
            DB::table('krs')->where('user_id', $studentId)->delete();

            // Masukkan data baru beserta status, kelas, dan angkatan default/statis dulu agar aman
            foreach ($request->matkul as $courseId) {
                DB::table('krs')->insert([
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'status' => 'Pending',
                    'kelas' => '41 TI',      // 🟢 Isikan kelas default ujian kuliah kamu di sini
                    'angkatan' => '2024',    // 🟢 Isikan angkatan default ujian kuliah kamu di sini
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->route('mahasiswa.krs')->with('success', 'Rencana studi (KRS) Anda berhasil diajukan! Menunggu validasi Dosen PA.');
    }

    // 🟢 FUNGSI BARU: Mengubah status di tabel krs menjadi 'Disetujui'
    public function approve($id)
    {
        DB::table('krs')->where('user_id', $id)->update([
            'status' => 'Disetujui',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'KRS Mahasiswa bersangkutan berhasil disetujui!');
    }
}