<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConsultationLogController extends Controller
{
 
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthenticated / Token tidak valid'
                ], 401);
            }

            // 🟢 PERBAIKAN: Ubah 'user_id' menjadi 'mahasiswa_id'
            $logs = DB::table('consultation_logs')
                ->where('mahasiswa_id', $user->id)
                ->orderBy('tanggal_bimbingan', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $logs
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat bimbingan: ' . $e->getMessage()
            ], 500);
        }
    }

  // 🟢 Ambil Daftar Dosen dari Tabel 'lecturers'
public function getDosen()
{
    try {
        // Ambil 'id' dan 'nama_dosen', lalu alias-kan 'nama_dosen' sebagai 'name'
        $dosen = DB::table('lecturers')
            ->select('id', 'nama_dosen as name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $dosen
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Gagal mengambil data dosen: ' . $e->getMessage()
        ], 500);
    }
}

   /**
 * 🟢 Simpan Pengajuan Bimbingan Baru dari Flutter
 */
/**
 * 🟢 Simpan Pengajuan Bimbingan Baru dari Flutter
 */
public function store(Request $request)
{
    try {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthenticated / Token tidak valid'
            ], 401);
        }

        $request->validate([
            'jenis_konsultasi'  => 'required|string',
            'topik_bimbingan'   => 'required|string',
            'tanggal_bimbingan' => 'required|date',
            'dosen_id'          => 'nullable',
        ]);

        // Insert menyesuaikan struktur tabel consultation_logs di database
        $id = DB::table('consultation_logs')->insertGetId([
            'mahasiswa_id'      => $user->id,
            'nama_mahasiswa'    => $user->name ?? 'Mahasiswa',
            'nim'               => $user->nim ?? '2455201110025',
            'jenis_konsultasi'  => $request->jenis_konsultasi,
            'lecturer_id'       => $request->dosen_id ?? 1, // Sesuaikan dengan nama kolom lecturer_id
            'room_id'           => $request->room_id ?? 1,   // Default room_id agar tidak kena Null Error
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan'   => $request->topik_bimbingan,
            'request_pertemuan' => $request->request_pertemuan ?? 'Tidak',
            'status_bimbingan'  => 'Menunggu Validasi',
            'nama_ruangan'      => 'Menunggu Konfirmasi',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Bimbingan Berhasil Disimpan!',
            'data_id' => $id
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()
        ], 500);
    }
}
}