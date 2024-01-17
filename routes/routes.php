<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Авторизация
 */
Route::middleware('web')->group(function () {
    Route::get('/login/', [LoginController::class, 'create'])->name('login');
    Route::post('/login/', [LoginController::class, 'store']);
    Route::post('/logout/', [LoginController::class, 'destroy'])->middleware('auth');
});

/**
 * Пользователи
 */
Route::middleware('web')->group(function () {
    Route::get('/', [PageController::class, 'showHome']);
});
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('users', UserController::class);
})->where(['user' => '[a-zA-Z0-9]+']);