<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassScheduleController extends Controller
{
   // 1. Tampilkan Data Hasil Join (Pastikan mengarah ke view 'jadwal.index')
   public function index()
    {
        $schedules = DB::table('class_schedules')
            ->join('courses', 'class_schedules.course_id', '=', 'courses.id')
            ->join('lecturers', 'class_schedules.lecturer_id', '=', 'lecturers.id')
            ->join('rooms', 'class_schedules.room_id', '=', 'rooms.id')
            ->select(
                'class_schedules.*',
                'courses.kode_mk',
                'courses.nama_mk',
                'courses.sks',
                'courses.status_validasi',
                'lecturers.nama_dosen',
                'rooms.nama_ruangan'
            )
            ->get();

        // Tarik data pendukung untuk modal
        $courses = DB::table('courses')->where('status_validasi', 'ACC')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

        return view('schedule.index', compact('schedules', 'courses', 'lecturers', 'rooms'));
    }

    // 2. Tampilkan Form Tambah Jadwal (Mengambil Data Master untuk Dropdown)
    public function create()
    {
        // 🔍 PERBAIKAN: Hanya mengambil mata kuliah yang sudah di-ACC Kaprodi
        $courses = DB::table('courses')->where('status_validasi', 'ACC')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

      return view('schedule.create', compact('courses', 'lecturers', 'rooms'));
    }

    // 3. Proses Simpan Jadwal Baru
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'lecturer_id' => 'required',
            'room_id' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kelas' => 'required',
        ]);

        DB::table('class_schedules')->insert([
            'course_id' => $request->course_id,
            'lecturer_id' => $request->lecturer_id,
            'room_id' => $request->room_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kelas' => $request->kelas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal Kuliah Berhasil Ditambahkan!');
    }

    /// 4. Tampilkan Form Edit Jadwal
    public function edit($id)
    {
        $schedule = DB::table('class_schedules')->where('id', $id)->first();
        
        // 🔍 PERBAIKAN: Hanya mengambil mata kuliah yang sudah di-ACC Kaprodi
        $courses = DB::table('courses')->where('status_validasi', 'ACC')->get();
        $lecturers = DB::table('lecturers')->get();
        $rooms = DB::table('rooms')->get();

       return view('schedule.edit', compact('schedule', 'courses', 'lecturers', 'rooms'));
    }

    // 5. Proses Update Data Jadwal
    public function update(Request $request, $id)
    {
        DB::table('class_schedules')->where('id', $id)->update([
            'course_id' => $request->course_id,
            'lecturer_id' => $request->lecturer_id,
            'room_id' => $request->room_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kelas' => $request->kelas,
            'updated_at' => now(),
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal Kuliah Berhasil Diperbarui!');
    }

    // 6. Proses Hapus Jadwal
    public function destroy($id)
    {
        DB::table('class_schedules')->where('id', $id)->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal Kuliah Berhasil Dihapus!');
    }

    // 🔍 FUNGSI BARU KELOLA STATUS DOSEN DAN NOTIFIKASI OTOMATIS
  public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_dosen' => 'required',
            'keterangan_status' => 'required',
        ]);

        // ✅ DATA UTAMA & CATATAN MASUK KE SINI DENGAN AMAN
        DB::table('class_schedules')->where('id', $id)->update([
            'status_dosen' => $request->status_dosen,
            'keterangan_status' => $request->keterangan_status, 
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status kehadiran dan catatan dosen berhasil diperbarui!');
    }
}