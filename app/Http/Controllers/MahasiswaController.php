<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    // 1. Menampilkan Tabel (Halaman Output)
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('tampil-mahasiswa', compact('mahasiswa'));
    }

    // TAMBAHKAN DI BAWAHNYA (Fungsi Baru)
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

    // 2. Menampilkan Form Tambah
    public function create()
{
    // 1. Ambil data dosen dari database
    $lecturers = DB::table('lecturers')->get();

    // 2. Lempar variabel $lecturers ke dalam view form mahasiswa kamu
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
    // 1. Ambil data mahasiswa yang mau diedit berdasarkan ID (Kodingan asli kamu)
    $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();

    // 2. AMBIL JUGA DATA DOSEN (Tambahkan baris ini)
    $lecturers = DB::table('lecturers')->get();

    // 3. Masukkan 'lecturers' ke dalam compact agar terlempar ke view edit
    return view('edit-mahasiswa', compact('mahasiswa', 'lecturers'));
}

    // 6. Update (Proses Perubahan)
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

    // 8. DASHBOARD (Satu fungsi yang sudah digabung & aman)
 public function dashboard()
{
    // Menggunakan try-catch agar jika database error, kita tahu penyebabnya
    try {
        $totalMhs = \App\Models\Mahasiswa::count();
        $totalLaki = \App\Models\Mahasiswa::where('jenis_kelamin', 'Laki-laki')->count();
        $totalPerempuan = \App\Models\Mahasiswa::where('jenis_kelamin', 'Perempuan')->count();
        
        $prodiData = \App\Models\Mahasiswa::select('prodi', DB::raw('count(*) as total'))
                    ->whereNotNull('prodi')
                    ->groupBy('prodi')->get();

        $asalData = \App\Models\Mahasiswa::select('alamat', DB::raw('count(*) as total'))
                    ->whereNotNull('alamat')
                    ->groupBy('alamat')->get();

        return view('dashboard', compact('totalMhs', 'totalLaki', 'totalPerempuan', 'prodiData', 'asalData'));
        
    } catch (\Exception $e) {
        // Jika ada error, Laravel akan menampilkan pesan error aslinya
        return "Terjadi Error di Controller: " . $e->getMessage();
    }
}


}