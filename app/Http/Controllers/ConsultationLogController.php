<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationLogController extends Controller
{
    // 1. Menampilkan Halaman Utama SIBIMBING (Join 3 Tabel)
    public function index()
    {
        $logs = DB::table('consultation_logs')
            ->join('mahasiswas', 'consultation_logs.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('lecturers', 'consultation_logs.lecturer_id', '=', 'lecturers.id')
            ->join('rooms', 'consultation_logs.room_id', '=', 'rooms.id')
            ->select(
                'consultation_logs.*',
                'mahasiswas.nim',
                'mahasiswas.nama as nama_mahasiswa',
                'lecturers.nama_dosen',
                'rooms.nama_ruangan'
            )
            ->get();

        return view('bimbingan.index', compact('logs'));
    }

    // 2. Menampilkan Form Tambah Bimbingan (Ambil Data Master untuk Dropdown)
    public function create()
    {
        $mahasiswas = DB::table('mahasiswas')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

        return view('bimbingan.create', compact('mahasiswas', 'lecturers', 'rooms'));
    }

    // 3. Memproses Simpan Bimbingan Baru
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required',
            'lecturer_id' => 'required',
            'room_id' => 'required',
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required',
            'status_bimbingan' => 'required',
        ]);

        DB::table('consultation_logs')->insert([
            'mahasiswa_id' => $request->mahasiswa_id,
            'lecturer_id' => $request->lecturer_id,
            'room_id' => $request->room_id,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'status_bimbingan' => $request->status_bimbingan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('bimbingan.index')->with('success', 'Log Bimbingan Berhasil Ditambahkan!');
    }

    // 4. Menampilkan Form Edit Bimbingan
    public function edit($id)
    {
        $log = DB::table('consultation_logs')->where('id', $id)->first();
        $mahasiswas = DB::table('mahasiswas')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

        return view('bimbingan.edit', compact('log', 'mahasiswas', 'lecturers', 'rooms'));
    }

    // 5. Memproses Update Data Bimbingan
    public function update(Request $request, $id)
    {
        $request->validate([
            'mahasiswa_id' => 'required',
            'lecturer_id' => 'required',
            'room_id' => 'required',
            'tanggal_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required',
            'status_bimbingan' => 'required',
        ]);

        DB::table('consultation_logs')->where('id', $id)->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'lecturer_id' => $request->lecturer_id,
            'room_id' => $request->room_id,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'status_bimbingan' => $request->status_bimbingan,
            'updated_at' => now(),
        ]);

        return redirect()->route('bimbingan.index')->with('success', 'Log Bimbingan Berhasil Diperbarui!');
    }

    // 6. Memproses Hapus Log Bimbingan
    public function destroy($id)
    {
        DB::table('consultation_logs')->where('id', $id)->delete();
        return redirect()->route('bimbingan.index')->with('success', 'Log Bimbingan Berhasil Dihapus!');
    }
}