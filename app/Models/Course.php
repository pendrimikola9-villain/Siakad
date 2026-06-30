<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // 🔍 Mengunci nama tabel agar sinkron dengan database Laragon
    protected $table = 'courses';

    // 🔍 Mendaftarkan kolom agar bisa diisi oleh Controller
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'status_validasi',
        'catatan_tolak'
    ];
}