<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Чтобы в БД значения хранились не в виде ["\u0434\u0438\u0441\u043a\u0438"], используем JSON_UNESCAPED_UNICODE
 */
class Json implements CastsAttributes
{

    public function get($model, $key, $value, $attributes)
    {
        return ($value !== null) ? json_decode($value, true) : $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
