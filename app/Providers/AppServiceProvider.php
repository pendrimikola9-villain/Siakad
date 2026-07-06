<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        // 💡 SOLUSI AMAN: Ambil notifikasi langsung dari tabel class_schedules yang kolomnya valid
        View::composer('*', function ($view) {
            if (Auth::check()) {
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
            } else {
                $view->with([
                    'globalNotifications' => collect(),
                    'globalNotificationCount' => 0
                ]);
            }
        });
    }
}