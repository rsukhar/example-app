<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt as CryptFacade;

/**
 * Обратимое шифрование строк
 */
class Crypt implements CastsAttributes
{
    public function get($model, $key, $value, $attributes): ?string
    {
        return CryptFacade::decryptString($value);
    }

    public function set($model, $key, $value, $attributes): string
    {
        return CryptFacade::encryptString($value);
    }
}
