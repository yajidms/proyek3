<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/profile', [ApiAuthController::class, 'profile']);
    Route::get('/courses', [ApiAuthController::class, 'courses']);
});
