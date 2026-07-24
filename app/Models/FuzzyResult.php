<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuzzyResult extends Model
{
    use HasFactory;

    protected $table = 'fuzzy_results';

    protected $fillable = [
        'user_id',
        'kehadiran_input',
        'tugas_input',
        'keaktifan_input',
        'hasil_sks_crisp',
        'kategori_rekomendasi',
    ];

    /**
     * Relasi ke Model User (Mahasiswa)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}