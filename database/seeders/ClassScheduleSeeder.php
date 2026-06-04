<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSchedule;
use App\Models\Room;
use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;

class ClassScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Mengambil ID pertama dari masing-masing tabel master yang sudah ada datanya
        $courseId = DB::table('courses')->value('id') ?? 1; // Jika tabel courses kosong, default id 1
        $lecturerId = Lecturer::value('id') ?? 1;
        $roomId = Room::value('id') ?? 1;

        ClassSchedule::create([
            'course_id' => $courseId,
            'lecturer_id' => $lecturerId,
            'room_id' => $roomId,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'kelas' => 'A'
        ]);
    }
}