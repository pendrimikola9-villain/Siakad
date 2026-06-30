<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    protected $fillable = ['mahasiswa_id', 'course_id', 'nilai', 'grade', 'status_kunci'];

    // Relasi ke data Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    // Relasi ke data Mata Kuliah (Course)
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}