<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create([
            'nama_ruangan' => 'Laboratorium Komputer 1',
            'kapasitas' => 30,
            'jenis_ruangan' => 'Laboratorium',
            'lokasi_gedung' => 'Gedung Utama Lt. 2'
        ]);

        Room::create([
            'nama_ruangan' => 'Ruang Kelas Teori 2A',
            'kapasitas' => 40,
            'jenis_ruangan' => 'Teori',
            'lokasi_gedung' => 'Gedung B Lt. 1'
        ]);
    }
}