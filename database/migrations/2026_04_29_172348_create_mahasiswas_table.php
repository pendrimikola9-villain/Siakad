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
       Schema::create('mahasiswas', function (Blueprint $table) {
        // 1. Primary Key (Wajib sesuai soal no. 2)
        $table->id(); 
        
        // Data Pribadi (6 field)
        $table->string('nim', 15)->unique();
        $table->string('nama', 100);
        $table->string('tempat_lahir', 50);
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
        $table->text('alamat');

        // Kontak & Sosial (4 field)
        $table->string('email')->unique();
        $table->string('no_hp', 15);
        $table->string('nama_ayah', 100);
        $table->string('nama_ibu', 100);

        // Akademik (5 field)
        $table->string('prodi', 50);
        $table->integer('semester');
        $table->string('dosen_pembimbing', 100);
        $table->decimal('ipk_terakhir', 3, 2)->default(0.00);
        $table->enum('status_mahasiswa', ['Aktif', 'Cuti', 'Alumni']);

        $table->timestamps(); // Created_at & Updated_at
    });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
