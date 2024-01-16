<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\InterestKeywordController;
use App\Http\Controllers\InterestSiteController;
use App\Http\Controllers\InterestUrlController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProxyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RobokassaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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
 * Интересы
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('interests', InterestController::class)->only(['index', 'update']);
    Route::get('interests/export', [InterestController::class, 'export']);
    Route::post('interests/publish', [InterestController::class, 'publish']);
    Route::post('interests/import_keys', [InterestController::class, 'import_keys']);
    Route::post('interests/import_urls', [InterestController::class, 'import_urls']);
    Route::post('interests/check_sites', [InterestController::class, 'check_sites']);
    // Ключевики интересов
    Route::resource('interests.keywords', InterestKeywordController::class)->only(['index']);
    // Сайты интересов
    Route::resource('interest_sites', InterestSiteController::class)->only('index');
    Route::post('interest_sites/check', [InterestSiteController::class, 'check']);
    Route::post('interest_sites/delete', [InterestSiteController::class, 'delete']);
    // Страницы интересов
    Route::resource('interest_urls', InterestUrlController::class)->only('index');
});

/**
 * Проекты
 */
Route::middleware(['web', 'auth'])->group(function () {
    // Общие действия: создание, редактирование, просмотр, удаление
    Route::resource('projects', ProjectController::class)->except(['edit']);
    // Статистика
    Route::get('projects/{project}/stats', [ProjectController::class, 'stats'])
        ->name('projects.stats');
    // Список интересов
    Route::get('projects/{project}/interests', [ProjectController::class, 'interests'])
        ->name('projects.interests');
    // Копирование
    Route::post('projects/{project}/copy', [ProjectController::class, 'copy']);
    // Редактирование
    Route::get('projects/{project}/edit-general', [ProjectController::class, 'editGeneral'])
        ->name('projects.edit-general');
    Route::get('projects/{project}/edit-interests', [ProjectController::class, 'editInterests'])
        ->name('projects.edit-interests');
    Route::get('projects/{project}/edit-checks', [ProjectController::class, 'editChecks'])
        ->name('projects.edit-checks');
});
// Переход по ссылке прокачки
Route::get('go-{type}/{secret}{format?}', [ProjectController::class, 'go'])
    ->where(['type' => 'ipv[46]', 'secret' => '[a-zA-Z\d]{24}', 'format' => '\.json']);
// Переход по ссылке проверки профиля
Route::get('check/{secret}', [ProjectController::class, 'check'])
    ->where(['secret' => '[a-zA-Z\d]{24}']);
// Импорт интересов из Яндекс-Метрики
Route::middleware('api')
    ->post('projects/import', [ProjectController::class, 'import']);

/**
 * Калькулятор прокачки
 */
Route::middleware(['web', 'auth'])->get('calc', [ToolsController::class, 'calc']);

/**
 * Отправка сообщений в телеграм
 */
Route::middleware(['web', 'auth'])->get('newsletter', [ToolsController::class, 'newsletterForm']);
Route::middleware(['web', 'auth'])->post('newsletter', [ToolsController::class, 'newsletterSend']);

/**
 * Настройки
 */
Route::middleware(['web', 'auth'])->group(function () {
    // Общие настройки
    Route::get('settings/{group}', [SettingController::class, 'showGroup']);
    Route::post('settings/{group}', [SettingController::class, 'updateGroup']);
    // Прокси
    Route::resource('proxies', ProxyController::class)
        ->only(['index', 'store', 'destroy']);
});

/**
 * Отчеты
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('reports/kpi', [ReportController::class, 'kpi']);
    Route::get('reports/subscriptions', [ReportController::class, 'subscriptions']);
});

/**
 * Пользователи
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('users', UserController::class);
    // Управление оповещениями
    Route::post('users/{user}/set_notification', [UserController::class, 'setNotification']);
    // Статистика переходов
    Route::get('users/{user}/stats', [UserController::class, 'stats'])->name('users.stats');
    // Баланс и список транзакций
    Route::get('users/{user}/transactions', [TransactionController::class, 'index'])
        ->name('users.billing');
    // Переключение тарифного плана
    Route::post('users/{user}/update-plan', [UserController::class, 'updatePlan']);
    // Добавление транзакций
    Route::post('users/{user}/transactions', [TransactionController::class, 'store']);
    // Редактирование и удаление транзакций
    Route::resource('transactions', TransactionController::class)->only(['update', 'destroy']);
})->where(['user' => '[a-zA-Z0-9]+']);

/**
 * Телеграм-бот
 */
Route::post('/telegram_webhook/<token>/', TelegramWebhookController::class)
    ->withoutMiddleware(['VerifyCsrfToken']);

/**
 * Прием платежей
 */
Route::withoutMiddleware(['VerifyCsrfToken'])->group(function () {
    Route::post('/robokassa/result_webhook', [RobokassaController::class, 'resultWebhook']);
    Route::get('/robokassa/success', [RobokassaController::class, 'showResult']);
    Route::get('/robokassa/fail', [RobokassaController::class, 'showResult']);
});
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/robokassa/pay', [RobokassaController::class, 'pay'])->name('robokassa.pay');
});

/**
 * Страницы
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('pages', PageController::class)->except(['show']);

    // Перемещение страниц в списке
    Route::post('/pages/{id}/move', [PageController::class, 'move']);
    // Загрузка и получение картинок
    Route::post('/pages/{id}/image', [PageController::class, 'uploadImage']);
    Route::get('/pages/{id}/{filename}', [PageController::class, 'getImage']);
});

if (config('app.debug', false)) {
    // Роуты для быстрых development-тестов
    Route::any('/dev/{action?}', [\App\Http\Controllers\DevController::class, 'index']);
}

/**
 * Страницы
 */
Route::middleware('web')->group(function () {
    Route::get('/', [PageController::class, 'showHome']);
    // Получение других страниц
    Route::get('/{url}', [PageController::class, 'show'])->where(['url' => '[a-zA-Z0-9-_\/]+'])
        ->name('pages.show');
});
