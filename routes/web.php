<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\AuthorizeMiddleware;

/**
 * Приветствие
 */
Route::get('/', fn() => inertia('Page/Home'))->middleware('auth');

/**
 * Пользователи
 */
Route::get('/login/', [UserController::class, 'login'])->name('login');
Route::post('/login/', [UserController::class, 'loginPost']);
Route::middleware('auth')->group(function () {
    Route::post('/logout/', [UserController::class, 'logout']);
    Route::resource('users', UserController::class)->scoped(['user' => 'username']);
})->where(['user' => '[a-zA-Z0-9]+']);

Route::resource('projects', ProjectController::class);

