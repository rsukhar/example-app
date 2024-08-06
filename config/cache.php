<?php
/**
 * Конфигурация кеширования
 * @docs https://github.com/laravel/laravel/blob/11.x/config/cache.php
 */

return [
    'default' => env('CACHE_STORE', 'database'),
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => env('REDIS_CACHE_CONNECTION', 'cache'),
            'lock_connection' => env('REDIS_CACHE_LOCK_CONNECTION', 'default'),
        ],
    ],
    'prefix' => env('CACHE_PREFIX', ''),
];
