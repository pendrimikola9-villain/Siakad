<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel fuzzy_results
     */
    public function up(): void
    {
        Schema::create('fuzzy_results', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan hasil fuzzy ke ID Mahasiswa di tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Variabel Input Fuzzy (Studi Kasus NIM Genap: Manajemen Belajar)
            $table->integer('kehadiran_input');     // Persentase Kehadiran (%)
            $table->decimal('tugas_input', 5, 2);   // Rata-Rata Nilai Tugas (0 - 100)
            $table->integer('keaktifan_input');     // Poin Keaktifan Diskusi (0 - 100)
            
            // Output Crisp & Kategori Rekomendasi
            $table->integer('hasil_sks_crisp');     // Hasil SKS (misal: 24 SKS)
            $table->string('kategori_rekomendasi'); // Misal: 'Paket Akselerasi (Performa Unggul)'
            
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_results');
    }
};