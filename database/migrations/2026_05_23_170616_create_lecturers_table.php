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
    Schema::create('lecturers', function (Blueprint $table) {
        // 1. ID Utama (Primary Key)
        $table->id(); 
        
        // 2 s.d 13: Field Informasi Dosen (Total 12 Field Teknis)
        $table->string('nidn', 20)->unique();
        $table->string('nik_karyawan', 20)->unique();
        $table->string('nama_dosen', 150);
        $table->string('tempat_lahir', 50);
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('no_hp', 20);
        $table->string('email_dosen', 100)->unique();
        $table->text('alamat_lengkap');
        $table->string('pendidikan_terakhir', 50);
        $table->string('jabatan_fungsional', 50);
        $table->string('bidang_keahlian', 100);
        
        // 14 & 15: created_at dan updated_at (Otomatis terhitung 2 field oleh Laravel)
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
