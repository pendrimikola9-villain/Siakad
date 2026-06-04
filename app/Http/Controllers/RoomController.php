<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    // 1. Tampilkan Daftar Ruangan
    public function index()
    {
        $rooms = Room::all();
        return view('room.index', compact('rooms'));
    }

    // 2. Tampilkan Form Tambah Ruangan
    public function create()
    {
        return view('room.create');
    }

    // 3. Proses Simpan Ruangan Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|max:50',
            'kapasitas' => 'required|integer|min:1',
            'jenis_ruangan' => 'required',
            'lokasi_gedung' => 'required|max:50',
        ]);

        Room::create($request->all());
        return redirect()->route('room.index')->with('success', 'Data Ruangan Berhasil Ditambahkan!');
    }

    // 4. Proses Hapus Ruangan (Opsional, untuk melengkapi aksi)
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('room.index')->with('success', 'Data Ruangan Berhasil Dihapus!');
    }

    // 4. Tampilkan Form Edit Ruangan
public function edit($id)
{
    $room = Room::findOrFail($id);
    return view('room.edit', compact('room'));
}

// 5. Proses Update Data Ruangan
public function update(Request $request, $id)
{
    $request->validate([
        'nama_ruangan' => 'required|max:50',
        'kapasitas' => 'required|integer|min:1',
        'jenis_ruangan' => 'required',
        'lokasi_gedung' => 'required|max:50',
    ]);

    $room = Room::findOrFail($id);
    $room->update($request->all());

    return redirect()->route('room.index')->with('success', 'Data Ruangan Berhasil Diperbarui!');
}
}