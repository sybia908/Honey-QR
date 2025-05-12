<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentScheduleController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Http\Request;
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

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // Endpoint untuk React UI
    Route::get('/dashboard/stats', [AuthController::class, 'dashboardStats']);

    // Student Schedule
    Route::get('/subjects', [StudentScheduleController::class, 'subjects']);
    Route::get('/schedule/today', [StudentScheduleController::class, 'todaySchedule']);

    // QR Code Attendance
    Route::post('/attendance/scan', [AttendanceController::class, 'scanQR']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);

    // Subjects Management
    Route::apiResource('subjects', SubjectController::class);
});
