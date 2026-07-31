<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : 1;
            $userName = $user ? $user->name : 'Mahasiswa';

            // 1. Coba ambil data presensi asli dari Database
            $presensi = DB::table('attendances')
                ->join('courses', 'attendances.course_id', '=', 'courses.id')
                ->where('attendances.mahasiswa_id', $userId)
                ->select(
                    'attendances.id',
                    'attendances.status',
                    'attendances.tanggal',
                    'courses.nama_mk',
                    'courses.kode_mk'
                )
                ->orderBy('attendances.tanggal', 'desc')
                ->get();

            // 2. 💡 JIKA DATA KOSONG, GUNAKAN DATA DUMMY DINAMIS
            if ($presensi->isEmpty()) {
                $presensi = collect([
                    [
                        'id' => 101,
                        'nama_mk' => 'Pemrograman Web 2',
                        'kode_mk' => 'INF4201',
                        'status' => 'Hadir',
                        'tanggal' => date('Y-m-d'),
                    ],
                    [
                        'id' => 102,
                        'nama_mk' => 'Rekayasa Perangkat Lunak',
                        'kode_mk' => 'INF4202',
                        'status' => 'Hadir',
                        'tanggal' => date('Y-m-d', strtotime('-1 day')),
                    ],
                    [
                        'id' => 103,
                        'nama_mk' => 'Kecerdasan Buatan (AI)',
                        'kode_mk' => 'INF4204',
                        'status' => 'Izin',
                        'tanggal' => date('Y-m-d', strtotime('-3 days')),
                    ],
                    [
                        'id' => 104,
                        'nama_mk' => 'Pemrograman Bergerak (Mobile)',
                        'kode_mk' => 'INF4205',
                        'status' => 'Hadir',
                        'tanggal' => date('Y-m-d', strtotime('-5 days')),
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil riwayat presensi',
                'user_logged_in' => $userName,
                'data'    => $presensi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat presensi: ' . $e->getMessage(),
                'data'    => []
            ], 200);
        }
    }

    // 🟢 Tambahkan di baris paling bawah sebelum kurung kurawal penutup '}'
    public function getEvaluasiFuzzyApi(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user ? $user->id : 1;

            // Ambil riwayat dari tabel fuzzy_results
            $riwayatFuzzy = DB::table('fuzzy_results')
                ->join('users', 'fuzzy_results.user_id', '=', 'users.id')
                ->select('fuzzy_results.*', 'users.name')
                ->orderBy('fuzzy_results.updated_at', 'desc')
                ->get();

            // Data evaluasi terbaru user yang login
            $evaluasiTerakhir = DB::table('fuzzy_results')
                ->where('user_id', $userId)
                ->latest('updated_at')
                ->first();

            return response()->json([
                'success'          => true,
                'jatahSksMaksimal' => $evaluasiTerakhir->hasil_sks_crisp ?? 24,
                'persenHadir'      => $evaluasiTerakhir->kehadiran_input ?? 100,
                'nilaiTugas'       => $evaluasiTerakhir->tugas_input ?? 90,
                'keaktifan'        => $evaluasiTerakhir->keaktifan_input ?? 85,
                'kategori'         => $evaluasiTerakhir->kategori_rekomendasi ?? 'Sangat Baik',
                'riwayatFuzzy'     => $riwayatFuzzy,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success'          => false,
                'message'          => 'Gagal memuat evaluasi fuzzy: ' . $e->getMessage(),
                'jatahSksMaksimal' => 24,
                'persenHadir'      => 100,
                'nilaiTugas'       => 90,
                'keaktifan'        => 85,
                'kategori'         => 'Sangat Baik',
                'riwayatFuzzy'     => [],
            ], 200);
        }
    }
}