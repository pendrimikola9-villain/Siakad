<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema; // 🟢 Tambahkan ini sebagai pengaman tabel

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 💡 SOLUSI AMAN & STABIL: Menggunakan penentu kondisi check session yang fleksibel
        View::composer('*', function ($view) {
            // Pastikan tabel class_schedules benar-benar ada dulu di database agar tidak memicu eror migrasi awal
            if (Auth::check() && Schema::hasTable('class_schedules')) {
                try {
                    // Ambil jadwal yang status dosennya bukan 'Berhadir' (berarti online/sakit)
                    $globalNotifications = DB::table('class_schedules')
                        ->join('courses', 'class_schedules.course_id', '=', 'courses.id')
                        ->whereNotNull('class_schedules.keterangan_status')
                        ->where('class_schedules.status_dosen', '!=', 'Berhadir')
                        ->select('class_schedules.*', 'courses.nama_mk')
                        ->orderBy('class_schedules.updated_at', 'desc')
                        ->take(5)
                        ->get();

                    $globalNotificationCount = DB::table('class_schedules')
                        ->whereNotNull('keterangan_status')
                        ->where('status_dosen', '!=', 'Berhadir')
                        ->count();

                    $view->with(compact('globalNotifications', 'globalNotificationCount'));
                } catch (\Exception $e) {
                    // Fallback darurat jika database sedang dikunci/migrasi ulang
                    $view->with([
                        'globalNotifications' => collect(),
                        'globalNotificationCount' => 0
                    ]);
                }
            } else {
                // Skenario aman jika dikunjungi oleh tamu (Guest) di halaman Landing Page
                $view->with([
                    'globalNotifications' => collect(),
                    'globalNotificationCount' => 0
                ]);
            }
        });
    }
}    