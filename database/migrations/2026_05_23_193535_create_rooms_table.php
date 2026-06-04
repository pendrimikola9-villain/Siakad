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
    Schema::create('rooms', function (Blueprint $table) {
        $table->id(); // id (PK)
        $table->string('nama_ruangan', 50); // Contoh: Lab Komputer 1, Ruang Teori 3
        $table->integer('kapasitas'); // Kolom kapasitas (Jumlah Mahasiswa)
        $table->enum('jenis_ruangan', ['Teori', 'Laboratorium', 'Aula'])->default('Teori'); // Tambahan biar genap terstruktur
        $table->string('lokasi_gedung', 50)->nullable(); // Contoh: Gedung A, Gedung B Kampus UMB
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
