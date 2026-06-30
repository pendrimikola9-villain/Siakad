<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultation_logs', function (Blueprint $table) {
            // Kolom Tambahan Pengidentifikasi Mahasiswa (Agar Match dengan Controller)
            $table->string('nama_mahasiswa')->nullable()->after('mahasiswa_id');
            $table->string('nim')->nullable()->after('nama_mahasiswa');

            // 1. Jenis Konsultasi (Skripsi, PKL, Magang, Tugas)
            $table->string('jenis_konsultasi')->default('Tugas Akhir / Skripsi')->after('nim');
            
            // 2. Fitur Janji Temu Hari Ini (Yes/No)
            $table->enum('request_pertemuan', ['Ya', 'Tidak'])->default('Tidak')->after('topik_bimbingan');
            
            // 3. Status Validasi & Alasan Penolakan
            $table->string('status_bimbingan')->default('Menunggu Validasi')->change();
            $table->text('alasan_penolakan')->nullable()->after('status_bimbingan');
            
            // 4. Nama Ruangan (Jika janji temu disetujui)
            $table->string('nama_ruangan')->default('Menunggu Konfirmasi')->after('alasan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_logs', function (Blueprint $table) {
            $table->dropColumn(['jenis_konsultasi', 'request_pertemuan', 'alasan_penolakan']);
        });
    }
};