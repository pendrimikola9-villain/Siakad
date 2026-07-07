<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Mahasiswa;

class GradeController extends Controller
{
    // 1. HALAMAN UTAMA INPUT NILAI (SISI DOSEN)
    public function index()
    {
        // Hanya mengambil mata kuliah yang sudah di-ACC oleh Kaprodi
        $courses = Course::where('status_validasi', 'ACC')->orderBy('nama_mk', 'asc')->get();
        
        // 🟢 UBAH: Dari $students menjadi $mahasiswa agar dibaca oleh file Blade
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get(); 
        
        // Mengambil semua data nilai yang sudah di-input beserta relasinya
        $grades = Grade::with(['mahasiswa', 'course'])->latest()->get();

        // 🟢 UBAH: Masukkan 'mahasiswa' ke dalam compact
        return view('grades.index', compact('courses', 'mahasiswa', 'grades'));
    }
    // 2. PROSES SIMPAN / UPDATE NILAI (DRAFT)
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required',
            'course_id' => 'required',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        // Logika konversi nilai angka ke nilai huruf (Grade)
       // Logika konversi nilai angka ke nilai huruf (Grade) - SINKRONISASI KAMPUS UMB
        $angka = $request->nilai;
        if ($angka >= 80) { $huruf = 'A'; }       // 🟢 Ubah dari 85 ke 80
        elseif ($angka >= 70) { $huruf = 'B'; }    // 🟢 Ubah dari 75 ke 70
        elseif ($angka >= 60) { $huruf = 'C'; }
        elseif ($angka >= 50) { $huruf = 'D'; }
        else { $huruf = 'E'; }

        // Simpan atau update jika mahasiswa dan mata kuliahnya sama
        Grade::updateOrCreate(
            [
                'mahasiswa_id' => $request->mahasiswa_id,
                'course_id' => $request->course_id,
            ],
            [
                'nilai' => $angka,
                'grade' => $huruf,
                'status_kunci' => 'Draft' // Otomatis berstatus Draft saat disimpan
            ]
        );

        return redirect()->back()->with('success', 'Nilai berhasil disimpan sebagai Draft!');
    }

    // 3. PROSES KUNCI NILAI (LOCK TO PERMANENT)
    public function kunciNilai($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->status_kunci = 'Locked';
        $grade->save();

        return response()->json([
            'success' => true,
            'message' => 'Nilai resmi dikunci dan disahkan ke KHS!'
        ]);
    }

    // =========================================================================
    // 🔍 4. PERBAIKAN TAMBAHAN: PROSES UNLOCK NILAI (HAK AKSES ADMIN/OPERATOR)
    // =========================================================================
    public function unlockNilai($id)
    {
        $grade = Grade::findOrFail($id);
        $grade->status_kunci = 'Draft'; // Status dikembalikan ke Draft agar bisa diedit lagi
        $grade->save();

        return response()->json([
            'success' => true,
            'message' => 'Kunci nilai berhasil dibuka! Sekarang data dapat diedit kembali.'
        ]);
    }

    // 5. FORM EDIT NILAI
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        
        // Proteksi: Jika sudah locked, tidak boleh di-edit via URL manual
        if ($grade->status_kunci === 'Locked') {
            return redirect()->back()->with('error', 'Nilai sudah dikunci, tidak bisa diedit!');
        }

        $courses = Course::where('status_validasi', 'ACC')->orderBy('nama_mk', 'asc')->get();
        $students = Mahasiswa::orderBy('nama', 'asc')->get();

        return view('dosen.edit_nilai', compact('grade', 'courses', 'students'));
    }

    // 6. PROSES UPDATE NILAI
    public function update(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $grade = Grade::findOrFail($id);

        if ($grade->status_kunci === 'Locked') {
            return redirect()->back()->with('error', 'Nilai sudah dikunci!');
        }

        // Hitung ulang Grade Huruf
        $angka = $request->nilai;
        if ($angka >= 80) { $huruf = 'A'; }
        elseif ($angka >= 70) { $huruf = 'B'; }
        elseif ($angka >= 60) { $huruf = 'C'; }
        elseif ($angka >= 50) { $huruf = 'D'; }
        else { $huruf = 'E'; }

        $grade->update([
            'nilai' => $angka,
            'grade' => $huruf,
        ]);

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil diperbarui!');
    }

    // 7. PROSES HAPUS NILAI
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);

        if ($grade->status_kunci === 'Locked') {
            return redirect()->back()->with('error', 'Nilai sudah dikunci, tidak bisa dihapus!');
        }

        $grade->delete();

        return redirect()->back()->with('success', 'Data nilai berhasil dihapus!');
    }
}