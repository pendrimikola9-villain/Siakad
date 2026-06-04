<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
   protected $fillable = [
    'nim', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
    'alamat', 'email', 'no_hp', 'nama_ayah', 'nama_ibu', 
    'prodi', 'semester', 'dosen_pembimbing', 'ipk_terakhir', 'status_mahasiswa'
];
}