<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecturer;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::all();
        return view('lecturer.index', compact('lecturers'));
    }

    public function create()
    {
        return view('lecturer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required|unique:lecturers,nidn',
            'nik_karyawan' => 'required|unique:lecturers,nik_karyawan',
            'nama_dosen' => 'required',
            'no_hp' => 'required',
            'email_dosen' => 'required|email|unique:lecturers,email_dosen',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'alamat_lengkap' => 'required',
            'pendidikan_terakhir' => 'required',
            'jabatan_fungsional' => 'required',
            'bidang_keahlian' => 'required',
        ]);

        Lecturer::create($request->all());
        return redirect()->route('dosen.index')->with('success', 'Data Dosen Berhasil Ditambahkan!');
    }

    // 4. MENAMPILKAN DETAIL DOSEN
    public function show($id)
    {
        $dosen = Lecturer::findOrFail($id);
        return view('lecturer.show', compact('dosen'));
    }

    // 5. MENAMPILKAN FORM EDIT DOSEN
    public function edit($id)
    {
        $dosen = Lecturer::findOrFail($id);
        return view('lecturer.edit', compact('dosen'));
    }

    // 6. MEMPROSES UPDATE DATA DOSEN
    public function update(Request $request, $id)
    {
        $dosen = Lecturer::findOrFail($id);
        
        $request->validate([
            'nidn' => 'required|unique:lecturers,nidn,'.$id,
            'nik_karyawan' => 'required|unique:lecturers,nik_karyawan,'.$id,
            'nama_dosen' => 'required',
            'no_hp' => 'required',
            'email_dosen' => 'required|email|unique:lecturers,email_dosen,'.$id,
            // ... field lainnya mengikuti bawaan form
        ]);

        $dosen->update($request->all());
        return redirect()->route('dosen.index')->with('success', 'Data Dosen Berhasil Diperbarui!');
    }

    // 7. MENGHAPUS DATA DOSEN
    public function destroy($id)
    {
        $dosen = Lecturer::findOrFail($id);
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Data Dosen Berhasil Dihapus!');
    }
}