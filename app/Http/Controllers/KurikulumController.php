<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KurikulumController extends Controller
{
    // =========================================================================
    // 1. SISI KAPRODI: TAMPILAN MASTER VALIDASI KURIKULUM
    // =========================================================================
    public function index()
    {
        $courses = Course::orderBy('kode_mk', 'asc')->get();
        return view('kaprodi.kurikulum', compact('courses'));
    }

    // =========================================================================
    // 2. SISI KAPRODI: PROSES AJAX ACC / TOLAK MATA KULIAH
    // =========================================================================
    public function validasi(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->status_validasi = $request->status;
        
        if ($request->status === 'Ditolak') {
            $course->catatan_tolak = $request->catatan;
        } else {
            $course->catatan_tolak = null;
        }
        
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Status validasi mata kuliah berhasil diperbarui!'
        ]);
    }

    // =========================================================================
    // 3. SISI ADMIN & OPERATOR: HALAMAN INPUT & TAMPILAN NILAI AKADEMIK
    // =========================================================================
    public function inputNilaiForm()
    {
        // 1. Ambil data master untuk dropdown modal input nilai baru
        $mahasiswa = DB::table('mahasiswas')->orderBy('nama', 'asc')->get();
        $courses = DB::table('courses')->where('status_validasi', 'ACC')->orderBy('nama_mk', 'asc')->get(); 
        
        // 2. Tarik data transaksi riil dari tabel nilai (hubungkan ke tabel mahasiswas dan courses)
        // Catatan: Ganti 'grades' di bawah ini sesuai nama asli tabel transaksi nilai kamu di HeidiSQL jika berbeda
        $grades = DB::table('grades')
            ->join('mahasiswas', 'grades.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('courses', 'grades.course_id', '=', 'courses.id')
            ->select(
                'grades.id',
                'mahasiswas.nama',
                'mahasiswas.nim',
                'courses.nama_mk',
                'courses.sks',
                'grades.nilai',
                'grades.grade',
                'grades.status_kunci'
            )
            ->get();

        // 3. 🟢 KUNCI PENTING: Jika file view kamu berada langsung di folder 'resources/views/tampil-nilai.blade.php',
        // maka ganti 'admin.input_nilai' di bawah ini menjadi 'tampil-nilai'
        return view('grades.index-blade', compact('mahasiswa', 'courses', 'grades'));
    }
}