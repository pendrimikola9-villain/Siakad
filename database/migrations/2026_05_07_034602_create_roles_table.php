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
    Schema::create('roles', function (Blueprint $table) {
        $table->id(); // Primary Key
        $table->string('name'); // Nama Role: Admin, Dosen, Mahasiswa
        $table->string('slug'); // Identitas unik (huruf kecil): admin, dosen, mahasiswa
$table->foreignId('role_id')->nullable()->constrained('roles');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
