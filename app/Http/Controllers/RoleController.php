<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Menampilkan Halaman Hak Akses & Daftar User
     */
    public function index()
    {
        // Ambil semua data user untuk ditampilkan di tabel konfigurasi live
        $users = DB::table('users')->orderBy('id', 'asc')->get();

        return view('roles.index', compact('users'));
    }

    /**
     * Memproses Perubahan Hak Akses / Role
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:operator,admin,kaprodi,dosen,mahasiswa',
        ]);

        // 1. Ambil data user target yang mau diubah
        $targetUser = DB::table('users')->where('id', $id)->first();

        if (!$targetUser) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // 🔒 2. PROTEKSI HIERARKI OPERATOR FAKULTAS VS ADMIN PRODI:
        if (Auth::user()->role === 'admin' && $targetUser->role === 'operator') {
            return redirect()->back()->with('error', 'Gagal! Admin tidak memiliki hak untuk mengubah role Operator Fakultas.');
        }

        // 3. Eksekusi update role di database
        DB::table('users')->where('id', $id)->update([
            'role' => $request->role,
            'updated_at' => now()
        ]);

        return redirect()->route('roles.index')->with('success', 'Hak akses atas nama ' . $targetUser->name . ' berhasil diperbarui!');
    }
}