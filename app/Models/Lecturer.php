<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang ada di database
    protected $table = 'lecturers';

    // Daftarkan semua field agar diizinkan untuk proses Input/CRUD oleh Laravel
    protected $fillable = [
        'nidn',
        'nik_karyawan',
        'nama_dosen',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'email_dosen',
        'alamat_lengkap',
        'pendidikan_terakhir',
        'jabatan_fungsional',
        'bidang_keahlian'
    ];
}