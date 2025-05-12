<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SupabaseExampleController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// React UI Route
Route::get('/react', function() {
    return view('react');
})->name('react');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Teacher Routes
    Route::middleware(['role:admin,guru'])->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Class Management
        Route::resource('classes', ClassController::class);

        // Student Management
        Route::resource('students', StudentController::class);
        Route::get('students/template/download', [StudentController::class, 'template'])->name('students.template');
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('students/export', [StudentController::class, 'export'])->name('students.export');

        // Subject Management
        Route::resource('subjects', SubjectController::class);

        // Teacher Management
        Route::resource('teachers', TeacherController::class);
        Route::post('/teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
        Route::get('/teachers/export', [TeacherController::class, 'export'])->name('teachers.export');

        // QR Code Management
        Route::resource('qrcodes', QRCodeController::class);

        // Attendance Management
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/{attendance}', [AttendanceController::class, 'show'])->name('attendances.show');
        Route::get('/attendances/export', [AttendanceController::class, 'export'])->name('attendances.export');
    });

    // Student Routes
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/scan', [QRCodeController::class, 'scan'])->name('qrcodes.scan');
        Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    });

    // Supabase Example Routes
    Route::prefix('supabase')->name('supabase.')->group(function () {
        Route::get('/example', [SupabaseExampleController::class, 'index'])->name('example');
        Route::post('/example', [SupabaseExampleController::class, 'store'])->name('example.store');
        Route::put('/example/{id}', [SupabaseExampleController::class, 'update'])->name('example.update');
        Route::delete('/example/{id}', [SupabaseExampleController::class, 'destroy'])->name('example.destroy');
        Route::post('/example/upload', [SupabaseExampleController::class, 'upload'])->name('example.upload');
    });

    // QR Code Verification (All authenticated users)
    Route::get('/qrcodes/{code}/verify', [QRCodeController::class, 'verify'])->name('qrcodes.verify');
});
