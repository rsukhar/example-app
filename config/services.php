<?php
/**
 * Конфигурации для интеграций со сторонними сервисами
 * @docs https://github.com/laravel/laravel/blob/11.x/config/services.php
 */

return [
    'dummy_json' => [
        'host' => env('DUMMY_JSON_HOST', 'https://dummyjson.com'),
        'login' => env('DUMMY_JSON_LOGIN', 'login'),
        'password' => env('DUMMY_JSON_PASSWORD', 'qwerty')
    ]
];
