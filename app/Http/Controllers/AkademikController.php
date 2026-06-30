<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkademikController extends Controller
{
    // 1. TAMPILKAN DATA
    public function indexCourse()
    {
        $courses = DB::table('courses')->get();
        return view('courses.index', compact('courses'));
    }

    // 2. SIMPAN DATA (Pastikan cuma ada SATU ini saja)
    public function storeCourse(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|unique:courses',
            'nama_mk' => 'required',
            'sks' => 'required|numeric',
            'semester' => 'required|numeric',
        ]);

        DB::table('courses')->insert([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Matakuliah berhasil ditambahkan!');
    }

    // 3. HAPUS DATA
    public function destroyCourse($id)
    {
        DB::table('courses')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Matakuliah berhasil dihapus!');
    }

    public function updateCourse(Request $request, $id)
{
    $request->validate([
        'kode_mk' => 'required',
        'nama_mk' => 'required',
        'sks' => 'required|numeric',
        'semester' => 'required|numeric',
    ]);

    DB::table('courses')->where('id', $id)->update([
        'kode_mk' => $request->kode_mk,
        'nama_mk' => $request->nama_mk,
        'sks' => $request->sks,
        'semester' => $request->semester,
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Matakuliah berhasil diperbarui!');
}

// Tambahkan di AkademikController.php

public function indexGrade()
{
    $mahasiswa = DB::table('mahasiswas')->get();
    $courses = DB::table('courses')->get();

    $grades = DB::table('grades')
        ->leftJoin('mahasiswas', 'grades.mahasiswa_id', '=', 'mahasiswas.id')
        ->leftJoin('courses', 'grades.course_id', '=', 'courses.id')
        ->select('grades.*', 'mahasiswas.nama', 'courses.nama_mk', 'courses.sks')
        ->orderBy('grades.created_at', 'desc') // Tambahkan ini agar data terbaru di atas
        ->get();

    return view('grades.index', compact('mahasiswa', 'courses', 'grades'));
}

public function storeGrade(Request $request)
{
    $request->validate([
        'mahasiswa_id' => 'required',
        'course_id' => 'required',
        'nilai' => 'required|numeric|min:0|max:100',
    ]);

    DB::table('grades')->insert([
        'mahasiswa_id' => $request->mahasiswa_id,
        'course_id' => $request->course_id,
        'nilai' => $request->nilai,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Nilai berhasil diinput!');
}

public function indexRole()
{
    // Mengarahkan ke file resources/views/roles/index.blade.php
    return view('roles.index');
}

public function storeNilai(Request $request) {
    // Validasi input
    $request->validate([
        'mahasiswa_id' => 'required',
        'course_id' => 'required',
        'nilai_angka' => 'required|numeric|min:0|max:100',
    ]);

    // Logika penentuan Grade (Otomatis)
    $angka = $request->nilai_angka;
    if($angka >= 80) $huruf = 'A';
    elseif($angka >= 70) $huruf = 'B';
    elseif($angka >= 60) $huruf = 'C';
    else $huruf = 'D';

    // Proses Transaksi: Simpan ke tabel grades
    DB::table('grades')->insert([
        'mahasiswa_id' => $request->mahasiswa_id,
        'course_id' => $request->course_id,
        'nilai_angka' => $angka,
        'grade' => $huruf,
        'created_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Transaksi Nilai Berhasil Simpan!');
}
}