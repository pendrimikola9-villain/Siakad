<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Menambahkan kolom role setelah kolom email dengan default 'mahasiswa'
            $blueprint->string('role')->default('mahasiswa');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Untuk menghapus kembali kolom jika migration di-rollback
            $blueprint->dropColumn('role');
        });
    }
};