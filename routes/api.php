<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ConsultationLogController;
use App\Http\Controllers\Api\ClassScheduleController;
use App\Http\Controllers\Api\UserController;
// 1. IMPORT AuthController kamu di sini (Sesuaikan foldernya jika berbeda)
use App\Http\Controllers\Api\AuthController; 

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// 2. TAMBAHKAN RUTE AUTENTIKASI DI SINI (Terbuka untuk publik)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 3. TAMBAHKAN RUTE LOGOUT DI DALAM MIDDLEWARE SANCTUM
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rute CRUD bawaan kamu sebelumnya
Route::apiResource('rooms', RoomController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('mahasiswas', MahasiswaController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('grades', GradeController::class);
Route::apiResource('consultation-logs', ConsultationLogController::class);
Route::apiResource('class-schedules', ClassScheduleController::class);
Route::apiResource('users', UserController::class);