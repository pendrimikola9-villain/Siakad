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
    Schema::create('grades', function (Blueprint $table) {
        $table->id();
        // Menggunakan foreignId agar Laravel tahu ini adalah relasi/koneksi antar tabel
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        
        $table->integer('nilai'); 
        $table->char('grade', 2)->nullable(); // Tambahan untuk menyimpan nilai huruf (A/B/C)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
