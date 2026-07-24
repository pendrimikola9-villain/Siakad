<?php

namespace App\Http\Controllers; // 🟢 Ruang nama disesuaikan dengan posisi folder luar

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar fungsi login bawaan web aman

class AuthController extends Controller
{
    // ================= FUNGSI REGISTER SIAKAD (NIM Masuk Kolom Email) =================
    public function register(Request $request)
    {
        // Tangkap data dari JSON raw body (menyesuaikan Flutter)
        $jsonData = $request->json()->all(); 

        // Validasi data inputan
        $validator = Validator::make($jsonData, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|max:255|unique:users,email', // NIM/Email harus unik
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $jsonData['name'],
            'email'    => $jsonData['email'],
            'password' => Hash::make($jsonData['password']),
            'role'     => 'mahasiswa', // Set default role registrasi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi SIAKAD berhasil',
            'user'    => $user
        ], 201);
    }

    // ================= FUNGSI LOGIN UNTUK WEB SIAKAD =================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Sesuaikan dengan logika otentikasi username/email di web kamu
        $user = User::where('email', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Selamat datang kembali di SIAKAD UMB!');
        }

        return redirect()->back()->withErrors(['login_error' => 'NIM/NIDN atau Password Anda salah.']);
    }

    // ================= FUNGSI LOGOUT UNTUK WEB SIAKAD =================
    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus sesi login user aktif di web

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Alihkan halaman kembali ke form login awal
        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem SIAKAD.');
    }
}