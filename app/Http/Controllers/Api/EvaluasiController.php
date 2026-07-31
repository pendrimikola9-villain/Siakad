<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluasiController extends Controller
{
    public function getEvaluasiFuzzyApi(Request $request)
{
    $user = Auth::user();
    
    // 🔴 KITA TEST: Kirim respon tegas
    return response()->json([
        'success'          => true,
        'jatahSksMaksimal' => 99, // Angka unik buat tes
        'persenHadir'      => 100,
        'nilaiTugas'       => 100,
        'keaktifan'        => 100,
        'kategori'         => 'TES API BARU',
        'riwayatFuzzy'     => [
            [
                'name' => $user ? $user->name : 'USER TIDAK TERBACA (NULL)',
                'kehadiran_input' => 100,
                'tugas_input' => 100,
                'keaktifan_input' => 100,
                'hasil_sks_crisp' => 99,
                'kategori_rekomendasi' => 'TES',
                'updated_at' => now()->toDateTimeString()
            ]
        ]
    ], 200);
}
}