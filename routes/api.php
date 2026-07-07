<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\LecturerController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ConsultationLogController;
use App\Http\Controllers\Api\ClassScheduleController;
use App\Http\Controllers\Api\UserController;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

Route::apiResource('rooms', RoomController::class);
Route::apiResource('roles', RoleController::class);
Route::apiResource('mahasiswas', MahasiswaController::class);
Route::apiResource('lecturers', LecturerController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('grades', GradeController::class);
Route::apiResource('consultation-logs', ConsultationLogController::class);
Route::apiResource('class-schedules', ClassScheduleController::class);
Route::apiResource('users', UserController::class);
