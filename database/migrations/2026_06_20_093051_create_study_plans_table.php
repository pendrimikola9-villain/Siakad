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
    Schema::create('study_plans', function (Blueprint $table) {
        $table->id();
        // Menyambung ke data mahasiswa (tabel users)
        $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
        // Menyambung ke katalog mata kuliah (tabel courses)
        $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
        $table->string('semester_akademik', 20); // Contoh: "2025/2026 Genap"
        $table->string('status', 30)->default('Draft'); // Draft, Menunggu ACC, Disetujui
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_plans');
    }
};
