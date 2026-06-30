<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Memproses Login Multi-Role
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required', // Menangkap input NIM dari form
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->username, // Memanfaatkan kolom email untuk penampung NIM
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Mengambil data user yang sedang login saat ini
            $user = Auth::user(); 

            // Cek role dan alihkan halaman sesuai perannya
            if ($user->role === 'admin') {
                return redirect()->intended('/'); // Admin masuk ke Dashboard Utama
            } elseif ($user->role === 'dosen') {
                return redirect()->intended('/dosen'); // Dosen masuk ke Data Dosen / Halaman Dosen
            } else {
                return redirect()->intended('/'); // Mahasiswa sementara diarahkan ke Dashboard/Halaman utama
            }
        }

        return back()->with('error', 'NIM atau Password salah!');
    }

    // Memproses Register Akun Baru
    public function register(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:users,email', // Validasi unik berdasarkan kolom email
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        // Menyimpan NIM ke dalam kolom 'email' bawaan agar tidak memicu error column
        DB::table('users')->insert([
            'name' => 'Mahasiswa ' . $request->nim,
            'email' => $request->nim, 
            'password' => Hash::make($request->password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Registrasi berhasil! Silakan login menggunakan NIM Anda.');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}