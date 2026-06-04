<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationLog extends Model
{
    use HasFactory;

    protected $table = 'consultation_logs';

    protected $fillable = [
        'mahasiswa_id',
        'lecturer_id',
        'room_id',
        'tanggal_bimbingan',
        'topik_bimbingan',
        'status_bimbingan'
    ];
}