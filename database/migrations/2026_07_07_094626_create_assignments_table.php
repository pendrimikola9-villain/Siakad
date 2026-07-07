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
    Schema::create('assignments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('course_id');
        $table->string('kategori')->nullable(); // 🟢 Tambahkan kolom kategori (Materi / Tugas)
        $table->string('judul_tugas');
        $table->text('deskripsi');
        $table->string('file_materi')->nullable();
        $table->dateTime('deadline')->nullable(); // 🟢 Tambahkan kolom deadline (Boleh kosong/null)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
