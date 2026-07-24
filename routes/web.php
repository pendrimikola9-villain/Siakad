<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ConsultationLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\TugasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Auth\RegisteredUserController;


/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Akademik UMB
|--------------------------------------------------------------------------
*/

// 🟢 1. LANDING PAGE (Alamat utama website untuk publik/tamu)
Route::get('/', function () {
    return view('welcome_page');
})->name('landing');

// 🟢 2. DASHBOARD UTAMA (Halaman dalam setelah sukses login)
Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

// Halaman Form Tampilan Login & Register (Gabung jadi satu di blade kamu)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.login'); })->name('register');

// Proses Backend Auth
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Lupa Password (Mailtrap)
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');


// =========================================================================
// 🔒 GROUP ROUTE INTERNAL (Hanya Bisa Diakses Setelah Login / Auth)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard Utama Internal
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

   // ==========================================
    // MASTER DATA MAHASISWA (PENGATURAN HAK AKSES)
    // ==========================================

    // 1. ROUTE BISA DIAKSES SEMUA ROLE (Admin, Operator, Kaprodi, Dosen) -> BISA LIHAT DATA & DETAIL
    Route::get('/data-mahasiswa', [MahasiswaController::class, 'index'])->name('data-mahasiswa');
    Route::get('/mahasiswa/show/{id}', [MahasiswaController::class, 'show'])->name('show-mahasiswa');
    Route::get('/create-mahasiswa', [MahasiswaController::class, 'create'])->name('create-mahasiswa'); // Tetap diizinkan agar Kaprodi/Dosen bisa melihat form dalam mode Read-Only

    // 2. ROUTE KHUSUS AKSI EKSEKUSI (HANYA ADMIN & OPERATOR) -> TAMBAH, UPDATE, DELETE
    // Kamu bisa menggunakan Pengecekan Middleware Role bawaan/kustom, ATAU pembatasan langsung
   // ==========================================
// MASTER DATA MAHASISWA
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/data-mahasiswa', [MahasiswaController::class, 'index'])->name('data-mahasiswa');
    Route::get('/create-mahasiswa', [MahasiswaController::class, 'create'])->name('create-mahasiswa');
    Route::post('/simpan-mahasiswa', [MahasiswaController::class, 'store'])->name('store-mahasiswa');
    Route::get('/mahasiswa/show/{id}', [MahasiswaController::class, 'show'])->name('show-mahasiswa');
    Route::get('/mahasiswa/edit/{id}', [MahasiswaController::class, 'edit'])->name('edit-mahasiswa');
    Route::put('/mahasiswa/update/{id}', [MahasiswaController::class, 'update'])->name('update-mahasiswa');
    Route::delete('/mahasiswa/delete/{id}', [MahasiswaController::class, 'destroy'])->name('delete-mahasiswa');
});

    // Master Data Dosen (CRUD)
    Route::get('/dosen', [LecturerController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/tambah', [LecturerController::class, 'create'])->name('dosen.create');
    Route::post('/dosen/simpan', [LecturerController::class, 'store'])->name('dosen.store');
    Route::get('/dosen/{id}', [LecturerController::class, 'show'])->name('dosen.show');
    Route::get('/dosen/{id}/edit', [LecturerController::class, 'edit'])->name('dosen.edit');
    Route::put('/dosen/{id}/update', [LecturerController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{id}/hapus', [LecturerController::class, 'destroy'])->name('dosen.destroy');

    // Master Data Ruangan / Lab (CRUD)
    Route::get('/ruangan', [RoomController::class, 'index'])->name('room.index');
    Route::get('/ruangan/tambah', [RoomController::class, 'create'])->name('room.create');
    Route::post('/ruangan/simpan', [RoomController::class, 'store'])->name('room.store');
    Route::get('/ruangan/{id}/edit', [RoomController::class, 'edit'])->name('room.edit');
    Route::put('/ruangan/{id}/update', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/ruangan/{id}/hapus', [RoomController::class, 'destroy'])->name('room.destroy');

    // Transaksi Manajemen Nilai (GradeController Baru)
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{id}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{id}/update', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{id}/delete', [GradeController::class, 'destroy'])->name('grades.destroy');
    
    // Lock & Unlock Nilai
    Route::post('/dosen/input-nilai/kunci/{id}', [GradeController::class, 'kunciNilai'])->name('dosen.nilai.kunci');
    Route::post('/admin/nilai/unlock/{id}', [GradeController::class, 'unlockNilai'])->name('admin.nilai.unlock');
    Route::get('/tampil-nilai', [MahasiswaController::class, 'tampilkanTransaksi'])->name('nilai.index');

    // Transaksi Jadwal Kuliah (SIPLAR)
    Route::resource('jadwal', ClassScheduleController::class);
    Route::post('/jadwal/update-status/{id}', [ClassScheduleController::class, 'updateStatus'])->name('jadwal.updateStatus');

    // Transaksi Log Bimbingan (SIBIMBING)
    Route::get('/bimbingan', [ConsultationLogController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/tambah', [ConsultationLogController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan/simpan', [ConsultationLogController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/{id}/edit', [ConsultationLogController::class, 'edit'])->name('bimbingan.edit');
    Route::put('/bimbingan/{id}/update', [ConsultationLogController::class, 'update'])->name('bimbingan.update');
    Route::delete('/bimbingan/{id}/hapus', [ConsultationLogController::class, 'destroy'])->name('bimbingan.destroy');
    Route::post('/bimbingan/status/{id}/{status}', [MahasiswaController::class, 'sibimbingUpdateStatus'])->name('bimbingan.status');

    // Manajemen Hak Akses (Roles)
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');

    // Kurikulum & Validasi (Sisi Kaprodi)
    Route::get('/kurikulum/validasi', [KurikulumController::class, 'index'])->name('kaprodi.kurikulum');
    Route::post('/kurikulum/update-status/{id}', [KurikulumController::class, 'validasi'])->name('kurikulum.updateStatus');
    Route::get('/laporan/akademik', [MahasiswaController::class, 'laporanAkademik'])->name('kaprodi.laporan');
    Route::get('/admin/input-nilai', [KurikulumController::class, 'inputNilaiForm'])->name('admin.nilai.form');
    Route::post('/admin/input-nilai/store', [KurikulumController::class, 'store'])->name('nilai.store');
    Route::get('/courses', [AkademikController::class, 'indexCourse'])->name('courses.index');
    Route::post('/courses/store', [AkademikController::class, 'storeCourse'])->name('courses.store');
    Route::put('/courses/update/{id}', [AkademikController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/delete/{id}', [AkademikController::class, 'destroyCourse'])->name('courses.destroy');

    // Fitur Khusus Sisi Mahasiswa (Tugas, KRS, Presensi)
    Route::get('/mahasiswa/tugas', [MahasiswaController::class, 'tugas'])->name('mahasiswa.tugas');
    Route::post('/tugas/store', [TugasController::class, 'store'])->name('tugas.store');
    Route::post('/tugas/kumpul/{id}', [TugasController::class, 'kumpulTugas'])->name('tugas.kumpul');
    
    Route::get('/mahasiswa/krs', [KrsController::class, 'index'])->name('mahasiswa.krs');
    Route::post('/mahasiswa/krs/simpan', [KrsController::class, 'simpan'])->name('mahasiswa.krs.simpan');
    Route::post('/krs/approve/{id}', [KrsController::class, 'approve'])->name('krs.approve');

    Route::get('/mahasiswa/siplar', [MahasiswaController::class, 'siplarIndex'])->name('mahasiswa.siplar');
    Route::get('/mahasiswa/sibimbing', [MahasiswaController::class, 'sibimbingIndex'])->name('mahasiswa.sibimbing');
    Route::post('/mahasiswa/sibimbing/store', [MahasiswaController::class, 'sibimbingStore'])->name('mahasiswa.bimbingan.store');

    Route::get('/mahasiswa/presensi', [PresensiController::class, 'index'])->name('mahasiswa.presensi');
    Route::post('/presensi/store-massal', [PresensiController::class, 'storeMassal'])->name('presensi.storeMassal');
    // Pastikan nama rutenya murni menggunakan ->name('presensi.getMahasiswa')
    Route::get('/presensi/get-mahasiswa', [PresensiController::class, 'getMahasiswaByFilter'])->name('presensi.getMahasiswa');


    Route::get('/mahasiswa/fuzzy-evaluasi', [KrsController::class, 'fuzzyEvaluasi'])->name('mahasiswa.fuzzy.evaluasi');
});