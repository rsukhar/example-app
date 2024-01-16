<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Чтобы хранить в БД хеши паролей, а не сами пароли
 */
class Hash implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        return \Illuminate\Support\Facades\Hash::make($value);
    }
}
