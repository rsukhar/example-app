<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Чтобы в БД значения хранились не в виде ["\u0434\u0438\u0441\u043a\u0438"], используем JSON_UNESCAPED_UNICODE
 */
class PhoneNumber implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): ?string
    {
        // Форматируем в виде +7-xxx-xxx-xx-xx
        return preg_replace(
            [
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ],
            [
                '+7-$2-$3-$4-$5',
                '+7-$2-$3-$4-$5',
                '+7-$2-$3-$4-$5',
                '+7-$2-$3-$4-$5',
                '+7-$2-$3-$4',
                '+7-$2-$3-$4',
            ],
            $value
        );
    }

    public function set($model, $key, $value, $attributes)
    {
        // Заменяем 8 на +7 в начале
        $value = preg_replace('~^8~', '+7', $value);

        // Убираем всё, кроме чисел
        return preg_replace('~[^\d]+~', '', $value);
    }
}
