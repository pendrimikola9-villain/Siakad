<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ConsultationLogController;
/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Akademik
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA (DASHBOARD)
Route::get('/', [MahasiswaController::class, 'dashboard'])->name('dashboard');


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

