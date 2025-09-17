<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:student')->group(function () {
        Route::get('/student/courses', [StudentController::class, 'courses'])->name('student.courses');
        Route::post('/student/courses/{course}/enroll', [StudentController::class, 'enroll'])->name('student.courses.enroll');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('courses', CourseController::class)->except(['show']);
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');
        Route::post('users/{user}/role', [AdminUserController::class, 'setRole'])->name('users.role');
    });
});
