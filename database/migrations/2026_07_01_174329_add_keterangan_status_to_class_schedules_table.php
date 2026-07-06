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
        // 🔹 PERBAIKAN: Menggunakan Blueprint, bukan Subtable
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->text('keterangan_status')->nullable()->after('status_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🔹 PERBAIKAN: Menggunakan Blueprint, bukan Subtable
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn('keterangan_status');
        });
    }
};