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
    { // 🔍 Perbaikan: Kurung kurawal pembuka dan ": void" sudah ditambahkan dengan benar
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); 
            $table->string('kode_mk', 10)->unique();
            $table->string('nama_mk', 100);
            $table->integer('sks');
            $table->integer('semester');
            
            // 🔍 Kolom status untuk logika validasi Kaprodi
            $table->enum('status_validasi', ['Pending', 'ACC', 'Ditolak'])->default('Pending');
            $table->text('catatan_tolak')->nullable();
            
            $table->timestamps();
        });
    } // 🔍 Perbaikan: Kurung kurawal penutup fungsi up()

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};