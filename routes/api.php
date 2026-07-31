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
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\KrsController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\EvaluasiController;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// RUTE PUBLIC
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// RUTE DENGAN PROTEKSI AUTH SANCTUM
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // 🟢 ENDPOINT KRS & COURSES
    Route::get('/courses', [KrsController::class, 'index']);
    Route::get('/krs', [KrsController::class, 'index']);
    Route::post('/krs', [KrsController::class, 'store']);

    // 🟢 RUTE KHUSUS KONSULTASI / SIBIMBING
    Route::get('/get-dosen', [ConsultationLogController::class, 'getDosen']);
    Route::apiResource('consultation-logs', ConsultationLogController::class);
    
    // 🟢 RUTE PRESENSI & ATTENDANCES
    Route::get('/presensi', [AttendanceController::class, 'index']);
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::get('/presensi/get-mahasiswa', [AttendanceController::class, 'index']);

    // 🟢 RUTE EVALUASI FUZZY
    Route::get('/evaluasi-fuzzy', [EvaluasiController::class, 'getEvaluasiFuzzyApi']);

    // 🟢 RUTE API RESOURCES LAINNYA
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('mahasiswas', MahasiswaController::class);
    Route::apiResource('lecturers', LecturerController::class);
    Route::apiResource('grades', GradeController::class);
    Route::apiResource('class-schedules', ClassScheduleController::class);
    Route::apiResource('users', UserController::class);
});