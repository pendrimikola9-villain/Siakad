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
    Schema::create('consultation_logs', function (Blueprint $table) {
        $table->id(); // id (PK)
        
        // Foreign Key Menghubungkan ke 3 Tabel Master sesuai laporan
        // Catatan: Pastikan nama tabel mahasiswa kamu 'mahasiswas' atau 'mahasiswa' (sesuaikan constrained-nya)
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
        $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        
        // Kolom Transaksi Bimbingan
        $table->date('tanggal_bimbingan');
        $table->text('topik_bimbingan'); // Isi pembahasan bimbingan/skripsi
        $table->enum('status_bimbingan', ['ACC', 'Revisi', 'Selesai'])->default('Revisi');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_logs');
    }
};
