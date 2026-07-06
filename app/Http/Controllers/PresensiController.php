<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        // 1. Ambil data mata kuliah dasar
        $rekapAbsen = DB::table('courses')
            ->select('id as course_id', 'nama_mk')
            ->get();

        // 2. Hitung statistik riil per mata kuliah langsung dari tabel attendances
        foreach ($rekapAbsen as $ra) {
            
            // Hitung total pertemuan unik berdasarkan tanggal yang ada di matkul ini
            $ra->total_pertemuan = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->distinct('tanggal')
                ->count('tanggal');

            // Hitung total status "Hadir" yang terekam di matkul ini
            $ra->total_hadir = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->where('status', 'Hadir')
                ->count();

            // Hitung total seluruh baris data di matkul ini untuk pembagi persentase
            $totalRecordMatkul = DB::table('attendances')
                ->where('course_id', $ra->course_id)
                ->count();

            // 3. Kalkulasi persentase pergerakan progress bar
            if ($totalRecordMatkul > 0 && $ra->total_pertemuan > 0) {
                $ra->persentase = round(($ra->total_hadir / $totalRecordMatkul) * 100);
            } else {
                $ra->persentase = 0;
            }
        }

        $daftarAngkatan = ['2022', '2023', '2024', '2025', '2026'];

        return view('mahasiswa.presensi', compact('rekapAbsen', 'daftarAngkatan')); 
    }

    // 🌐 FUNGSI: Mengambil semua data mahasiswa
    public function getMahasiswaByFilter(Request $request)
    {
        $courseId = $request->course_id;

        $mahasiswa = DB::table('mahasiswas')
            ->select('id', 'nama as name', 'nim as npm')
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json([
            'mahasiswa' => $mahasiswa,
            'course_id' => $courseId
        ]);
    }

  public function storeMassal(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        $courseId = $request->course_id;
        $tanggal = $request->tanggal;

        // 🟢 SOLUSI PINTAR: Tarik ID asli dari tabel users yang rolenya 'mahasiswa' 
        // Ini dilakukan agar lolos dan cocok dengan FOREIGN KEY (mahasiswa_id -> users.id)
        $mahasiswaUserIds = DB::table('users')
            ->where('role', 'mahasiswa')
            ->pluck('id')
            ->toArray();

        // Cadangan darurat: Jika di tabel users rolenya kosong, gunakan fallback id user login agar tidak crash
        if (empty($mahasiswaUserIds)) {
            $mahasiswaUserIds = [Auth::id()];
        }

        DB::transaction(function () use ($request, $courseId, $tanggal, $mahasiswaUserIds) {
            // Bersihkan data lama pada tanggal dan matkul ini agar tidak duplikat
            DB::table('attendances')
                ->where('course_id', $courseId)
                ->where('tanggal', $tanggal)
                ->delete();

            // Loop menggunakan ID yang valid dari tabel users
            foreach ($mahasiswaUserIds as $userId) {
                // Ambil status dari form, jika form tidak mengirimkan data array status, default diset 'Hadir'
                $statusKehadiran = $request->input("status.{$userId}", 'Hadir');

                DB::table('attendances')->insert([
                    'course_id' => $courseId,
                    'mahasiswa_id' => $userId, // Pastikan ID ini terdaftar di tabel users
                    'tanggal' => $tanggal,
                    'status' => $statusKehadiran,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->back()->with('success', 'Presensi berhasil disimpan! Data attendances lolos foreign key dan diperbarui.');
    }
}