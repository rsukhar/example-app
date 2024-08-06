<?php

use App\Http\Controllers\DevController;
use Illuminate\Support\Facades\Route;

if (config('app.debug', false)) {
    // Роуты для быстрых development-тестов
    Route::any('/dev/{action?}', [DevController::class, 'index']);
}
