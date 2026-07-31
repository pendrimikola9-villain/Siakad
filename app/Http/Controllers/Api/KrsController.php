<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    // ================= 1. AMBIL DAFTAR MATA KULIAH =================
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->has('filter_semester') && $request->filter_semester != '') {
            $query->where('semester', $request->filter_semester);
        }

        $katalogMatkul = $query->get();

        // 🟢 PENTING: Langsung kirim $katalogMatkul di dalam 'data' agar Flutter bisa membaca List-nya
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data mata kuliah',
            'data'    => $katalogMatkul
        ], 200);
    }

    // ================= 2. SIMPAN / AJUKAN KRS (UNTUK FLUTTER) =================
   // ================= SIMPAN / AJUKAN KRS =================
public function store(Request $request)
{
    $request->validate([
        'matkul_ids' => 'required|array',
    ]);

    $user = Auth::user();

    foreach ($request->matkul_ids as $courseId) {
        Krs::updateOrCreate(
            [
                'user_id'   => $user->id ?? 1,
                'course_id' => $courseId, // 👈 Sudah disesuaikan dengan kolom DB!
            ],
            [
                'status' => 'Pending'
            ]
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Pengajuan KRS Berhasil!'
    ], 200);
}
}