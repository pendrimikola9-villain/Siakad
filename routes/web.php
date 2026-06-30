<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ConsultationLogController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\GradeController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Akademik
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA (DASHBOARD)
// Pastikan rute dashboard kamu dibungkus atau ditempeli middleware auth
Route::get('/', [App\Http\Controllers\MahasiswaController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');


// 2. MANAGEMENT MAHASISWA (CRUD)
// Menampilkan Tabel Data Mahasiswa
Route::get('/data-mahasiswa', [MahasiswaController::class, 'index'])
    ->name('data-mahasiswa');

// Menampilkan Form Tambah Mahasiswa
Route::get('/create-mahasiswa', [MahasiswaController::class, 'create'])
    ->name('create-mahasiswa');

// Memproses Simpan Data Baru
Route::post('/simpan-mahasiswa', [MahasiswaController::class, 'store'])
   ->name('store-mahasiswa');

// Menampilkan Detail Mahasiswa (Tombol Biru)
Route::get('/mahasiswa/show/{id}', [MahasiswaController::class, 'show'])
    ->name('show-mahasiswa');

// Menampilkan Form Edit (Tombol Kuning)
Route::get('/mahasiswa/edit/{id}', [MahasiswaController::class, 'edit'])
    ->name('edit-mahasiswa');

// Memproses Update Data
Route::put('/mahasiswa/update/{id}', [MahasiswaController::class, 'update'])
->name('update-mahasiswa');

// Menghapus Data (Tombol Merah)
// Menggunakan DELETE agar sinkron dengan @method('DELETE') di Blade
Route::delete('/mahasiswa/delete/{id}', [MahasiswaController::class, 'destroy'])
    ->name('delete-mahasiswa');


// 3. MANAGEMENT AKADEMIK & NILAI
Route::get('/courses', [AkademikController::class, 'indexCourse'])
->name('courses.index');

Route::get('/grades', [AkademikController::class, 'indexGrade'])
    ->name('grades.index');


// 4. MANAGEMENT HAK AKSES (ROLES)

Route::get('/roles', [AkademikController::class, 'indexRole'])->name('roles.index');

// Route Matakuliah
Route::get('/courses', [AkademikController::class, 'indexCourse'])->name('courses.index');
Route::post('/courses/store', [AkademikController::class, 'storeCourse'])->name('courses.store');
Route::delete('/courses/delete/{id}', [AkademikController::class, 'destroyCourse'])->name('courses.destroy');
// Route Nilai
Route::get('/grades', [AkademikController::class, 'indexGrade'])->name('grades.index');

Route::put('/courses/update/{id}', [AkademikController::class, 'updateCourse'])->name('courses.update');

Route::get('/grades', [AkademikController::class, 'indexGrade'])->name('grades.index');
Route::post('/grades/store', [AkademikController::class, 'storeGrade'])->name('grades.store');

Route::get('/tampil-nilai', [MahasiswaController::class, 'tampilkanTransaksi'])->name('nilai.index');

// Route untuk menampilkan halaman daftar dosen
Route::get('/dosen', [LecturerController::class, 'index'])->name('dosen.index');

// Rute Lengkap CRUD Master Data Dosen
Route::get('/dosen', [LecturerController::class, 'index'])->name('dosen.index');
Route::get('/dosen/tambah', [LecturerController::class, 'create'])->name('dosen.create');
Route::post('/dosen/simpan', [LecturerController::class, 'store'])->name('dosen.store');
Route::get('/dosen/{id}', [LecturerController::class, 'show'])->name('dosen.show');
Route::get('/dosen/{id}/edit', [LecturerController::class, 'edit'])->name('dosen.edit');
Route::put('/dosen/{id}/update', [LecturerController::class, 'update'])->name('dosen.update');
Route::delete('/dosen/{id}/hapus', [LecturerController::class, 'destroy'])->name('dosen.destroy');

// Rute CRUD Master Data Ruangan
Route::get('/ruangan', [RoomController::class, 'index'])->name('room.index');
Route::get('/ruangan/tambah', [RoomController::class, 'create'])->name('room.create');
Route::post('/ruangan/simpan', [RoomController::class, 'store'])->name('room.store');
Route::delete('/ruangan/{id}/hapus', [RoomController::class, 'destroy'])->name('room.destroy');
Route::get('/ruangan/{id}/edit', [RoomController::class, 'edit'])->name('room.edit');
Route::put('/ruangan/{id}/update', [RoomController::class, 'update'])->name('room.update');

// Rute CRUD Lengkap Transaksi Jadwal Kuliah (SIPLAR)
Route::get('/jadwal', [ClassScheduleController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/tambah', [ClassScheduleController::class, 'create'])->name('jadwal.create');
Route::post('/jadwal/simpan', [ClassScheduleController::class, 'store'])->name('jadwal.store');
Route::get('/jadwal/{id}/edit', [ClassScheduleController::class, 'edit'])->name('jadwal.edit');
Route::put('/jadwal/{id}/update', [ClassScheduleController::class, 'update'])->name('jadwal.update');
Route::delete('/jadwal/{id}/hapus', [ClassScheduleController::class, 'destroy'])->name('jadwal.destroy');

// Rute CRUD Lengkap Transaksi Log Bimbingan (SIBIMBING)
Route::get('/bimbingan', [ConsultationLogController::class, 'index'])->name('bimbingan.index');
Route::get('/bimbingan/tambah', [ConsultationLogController::class, 'create'])->name('bimbingan.create');
Route::post('/bimbingan/simpan', [ConsultationLogController::class, 'store'])->name('bimbingan.store');
Route::get('/bimbingan/{id}/edit', [ConsultationLogController::class, 'edit'])->name('bimbingan.edit');
Route::put('/bimbingan/{id}/update', [ConsultationLogController::class, 'update'])->name('bimbingan.update');
Route::delete('/bimbingan/{id}/hapus', [ConsultationLogController::class, 'destroy'])->name('bimbingan.destroy');

// Tampilan Halaman Login & Register
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Proses Backend Auth
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/// 1. Rute untuk menampilkan halaman input email (Lupa Password)
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->name('password.request');

// 2. Rute mandiri untuk memproses pengiriman link reset ke Mailtrap
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    // Mengirimkan instruksi reset password bawaan Laravel via email
    $status = Password::sendResetLink($request->only('email'));

    // Jika sukses, kembali dengan pesan status
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::middleware(['auth'])->group(function () {
    // Rute yang sudah ada milikmu...

    // RUTE BARU SESI 1: Fitur Mahasiswa
    Route::get('/mahasiswa/presensi', [App\Http\Controllers\MahasiswaController::class, 'presensi'])
        ->name('mahasiswa.presensi');
        
    Route::get('/mahasiswa/tugas', [App\Http\Controllers\MahasiswaController::class, 'tugas'])
        ->name('mahasiswa.tugas');
});

Route::get('/mahasiswa/krs', [MahasiswaController::class, 'krsIndex'])->name('mahasiswa.krs');
Route::post('/mahasiswa/krs/simpan', [MahasiswaController::class, 'krsSimpan'])->name('mahasiswa.krs.simpan');

Route::get('/mahasiswa/siplar', [MahasiswaController::class, 'siplarIndex'])->name('mahasiswa.siplar');
Route::get('/mahasiswa/sibimbing', [MahasiswaController::class, 'sibimbingIndex'])->name('mahasiswa.sibimbing');
Route::post('/mahasiswa/sibimbing/store', [MahasiswaController::class, 'sibimbingStore'])->name('bimbingan.store');

// Rute untuk menampilkan halaman hak akses & tabel user
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

// Rute untuk memproses perubahan role via dropdown select
Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');

// =========================================================================
// 🔍 RUTE KHUSUS MANAJEMEN AKADEMIK KAPRODI & ADMIN
// =========================================================================

// 1. Tampilan Halaman Validasi Kurikulum (Nama disesuaikan dengan isi sidebar app.blade.php)
Route::get('/kurikulum/validasi', [App\Http\Controllers\KurikulumController::class, 'index'])->name('kaprodi.kurikulum');

// 2. Proses AJAX Update Status Kurikulum ke Database
Route::post('/kurikulum/update-status/{id}', [App\Http\Controllers\KurikulumController::class, 'validasi'])->name('kurikulum.updateStatus');

// 3. Tampilan Halaman Laporan Akademik
Route::get('/laporan/akademik', [App\Http\Controllers\MahasiswaController::class, 'laporanAkademik'])->name('kaprodi.laporan');

// 4. Tampilan Halaman Input Nilai Berstatus Pending (Admin/Operator)
Route::get('/admin/input-nilai', [App\Http\Controllers\KurikulumController::class, 'inputNilaiForm'])->name('nilai.index');

// Jalur Input Nilai Versi Dosen (Alur 1)
Route::get('/dosen/input-nilai', [GradeController::class, 'index'])->name('dosen.nilai.index');
Route::post('/dosen/input-nilai/store', [GradeController::class, 'store'])->name('dosen.nilai.store');
Route::post('/dosen/input-nilai/kunci/{id}', [GradeController::class, 'kunciNilai'])->name('dosen.nilai.kunci');
Route::post('/admin/nilai/unlock/{id}', [App\Http\Controllers\GradeController::class, 'unlockNilai'])->name('admin.nilai.unlock');