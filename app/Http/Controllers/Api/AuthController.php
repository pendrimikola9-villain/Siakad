<?php

namespace App\Http\Controllers\Api; // 🟢 Wajib berada di namespace Api

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ================= FUNGSI REGISTER API =================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|max:255|unique:users,email', // NIM/Email
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
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',
        ]);

        // Generasi Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi SIAKAD berhasil',
            'token'   => $token,
            'user'    => $user
        ], 201);
    }

   // ================= FUNGSI LOGIN API (FLUTTER) =================
public function login(Request $request)
{
    // Tangkap input 'email' dari Flutter (yang berisi NIM)
    $loginInput = $request->input('email') ?? $request->input('username');

    if (!$loginInput || !$request->password) {
        return response()->json([
            'success' => false,
            'message' => 'NIM / Nomor Identitas dan Kata Sandi wajib diisi!'
        ], 422);
    }

    // 🟢 HANYA CARI DI KOLOM 'email' (Tanpa orWhere 'nim')
    $user = User::where('email', $loginInput)->first();

    // Cek User & Password
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'NIM / Nomor Identitas atau Password Anda salah.'
        ], 401);
    }

    // Generate Token Sanctum khusus Flutter
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login SIAKAD Berhasil',
        'token'   => $token,
        'user'    => $user
    ], 200);
}

    // ================= FUNGSI LOGOUT API =================
    public function logout(Request $request)
    {
        // Hapus token aktif di database
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar dari sistem SIAKAD.'
        ], 200);
    }
}