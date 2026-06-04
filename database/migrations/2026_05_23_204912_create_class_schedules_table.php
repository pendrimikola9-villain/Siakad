<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('class_schedules', function (Blueprint $table) {
        $table->id(); // id (PK)
        
        // Foreign Key Menghubungkan ke 3 Tabel Master
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
        $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        
        // Kolom Tambahan Transaksi Jadwal
        $table->string('hari', 20); // Contoh: Senin, Selasa
        $table->time('jam_mulai');  // Contoh: 08:00
        $table->time('jam_selesai'); // Contoh: 09:40
        $table->string('kelas', 10); // Contoh: A, B, atau C
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
