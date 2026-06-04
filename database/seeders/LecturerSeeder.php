<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lecturer;

class LecturerSeeder extends Seeder
{
    public function run(): void
    {
        Lecturer::create([
            'nidn' => '1122334455',
            'nik_karyawan' => 'UMB-2026-001',
            'nama_dosen' => 'Dr. Ahmad Fauzi, M.T.',
            'tempat_lahir' => 'Banjarmasin',
            'tanggal_lahir' => '1985-05-12',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'email_dosen' => 'ahmad.fauzi@umb.ac.id',
            'alamat_lengkap' => 'Jl. Alalak Utara, Banjarmasin',
            'pendidikan_terakhir' => 'S3 Ilmu Komputer',
            'jabatan_fungsional' => 'Lektor',
            'bidang_keahlian' => 'Web Development & Cloud Computing',
        ]);
    }
}