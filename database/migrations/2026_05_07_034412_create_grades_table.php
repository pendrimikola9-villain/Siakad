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
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        
        $table->integer('nilai'); 
        $table->char('grade', 2)->nullable(); // Menyimpan nilai huruf (A, B, C, dll)
        
        // 🔍 Tambahkan kolom status kunci untuk Dosen (Draft / Locked)
        $table->enum('status_kunci', ['Draft', 'Locked'])->default('Draft');
        
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
