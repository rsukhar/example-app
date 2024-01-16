<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Html implements CastsAttributes
{
    /**
     * Преобразовать значение к пользовательскому типу.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     *
     * @return string
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value ?? '';
    }

    /**
     * Подготовить переданное значение к сохранению.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return ($value) ? Purify::config('default')->clean($value) : '';
    }
}
