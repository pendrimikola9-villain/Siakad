<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input form tugas
        $request->validate([
            'course_id'   => 'required',
            'judul_tugas' => 'required|string|max:255',
            'deskripsi'   => 'required',
        ]);

        // Proses upload file jika ada
        $fileName = null;
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tugas'), $fileName);
        }

        // Simpan data transaksi ke tabel assignments
       // Simpan data transaksi ke tabel assignments
        DB::table('assignments')->insert([
            'course_id'    => $request->course_id,
            'kategori'     => $request->kategori, // 🟢 Tangkap kolom kategori
            'judul_tugas'  => $request->judul_tugas,
            'deskripsi'    => $request->deskripsi,
            'file_materi'  => $fileName,
            'deadline'     => $request->deadline, // 🟢 Tangkap kolom deadline
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', 'Tugas/Materi perkuliahan berhasil dipublish!');
    }
}