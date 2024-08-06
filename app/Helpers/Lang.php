<?php

namespace App\Helpers;

class Lang {
    /**
     * Возвращает число с числительным, подходящим по словоформе
     *
     * @param int $number Число
     * @param array $forms Словоформы для 1, 2 и 5 единиц
     *
     * @return mixed
     */
    public static function ruPlural( int $number, array $forms = [ 'штука', 'штуки', 'штук' ] ): string {
        if ( $number % 10 === 1 && $number % 100 !== 11 ) {
            return $number . ' ' . $forms[0];
        } elseif ( $number % 10 >= 2 && $number % 10 <= 4 && ( $number % 100 < 10 || $number % 100 >= 20 ) ) {
            return $number . ' ' . $forms[1];
        } else {
            return $number . ' ' . $forms[2];
        }
    }
}
