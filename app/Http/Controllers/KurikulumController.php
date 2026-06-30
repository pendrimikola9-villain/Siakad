<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course; // 🔍 KUNCI PERBAIKAN: Tambahkan baris ini jika belum ada!
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KurikulumController extends Controller
{
    // =========================================================================
    // 1. SISI KAPRODI: TAMPILAN MASTER VALIDASI KURIKULUM
    // =========================================================================
    public function index()
    {
        // Sekarang sistem tidak akan bingung lagi mencari di mana model Course berada
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
            $course->catatan_tolak = null; // Bersihkan catatan jika di-ACC
        }
        
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Status validasi mata kuliah berhasil diperbarui!'
        ]);
    }

    // =========================================================================
    // 3. SISI ADMIN & OPERATOR: HALAMAN INPUT NILAI BERSTATUS PENDING
    // =========================================================================
    public function inputNilaiForm()
    {
        // Ambil data mahasiswa dan mata kuliah yang sudah di-ACC untuk dropdown form
        $students = DB::table('mahasiswas')->orderBy('nama', 'asc')->get();
        $courses = Course::where('status_validasi', 'ACC')->orderBy('nama_mk', 'asc')->get(); 
        
        // Ambil riwayat nilai dari tabel 'consultation_logs' atau tabel khusus nilai jika ada.
        // Sementara kita buat array dummy penampung riwayat agar halaman aman dan tidak crash.
        $grades = [
            (object)[
                'id' => 1,
                'nim' => '2455201110020',
                'nama_mhs' => 'PENDRI MIKOLA',
                'nama_mk' => 'Pemrograman Web 2',
                'nilai_angka' => 88.50,
                'nilai_huruf' => 'A',
                'status_nilai' => 'Pending',
                'catatan_revisi' => null
            ],
            (object)[
                'id' => 2,
                'nim' => '2455201110002',
                'nama_mhs' => 'AKMAL MAULANA YUSUF',
                'nama_mk' => 'Rekayasa Perangkat Lunak',
                'nilai_angka' => 76.00,
                'nilai_huruf' => 'B+',
                'status_nilai' => 'Sah (ACC)',
                'catatan_revisi' => null
            ]
        ];

        return view('admin.input_nilai', compact('students', 'courses', 'grades'));
    }
}