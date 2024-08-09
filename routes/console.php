<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Оповещение о предстоящем/произошедшем завершении ключа
//Schedule::call([\App\Models\User::class, 'downgradeExpired'])
//    ->name('Понизить тарифный план у пользователей с истекшей лицензией')
//    ->everyMinute();

//Artisan::command('inspire', function () {
//    $this->comment(Inspiring::quote());
//})->purpose('Display an inspiring quote')->hourly();