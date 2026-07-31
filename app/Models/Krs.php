<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'user_id',
        'course_id', // 👈 Pakai course_id
        'status',
        'kelas',
        'angkatan',
    ];
}