<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/**
 * Пользователи
 */
Route::middleware('web')->group(function () {
    Route::get('/login/', [UserController::class, 'login'])->name('login');
    Route::post('/login/', [UserController::class, 'loginForm']);
});
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/logout/', [UserController::class, 'logout']);
    Route::resource('users', UserController::class);
})->where(['user' => '[a-zA-Z0-9]+']);