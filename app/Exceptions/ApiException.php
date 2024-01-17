<?php

namespace App\Exceptions;

class ApiException extends \Exception
{
    /**
     * Выбросить оповещение об ошибке
     *
     * @param string $message Сообщение об ошибке
     * @param bool $canRetry Целесообразно ли повторить попытку?
     * @param ?int $code Код ошибки
     */
    public function __construct(
        string $message,
        public bool $canRetry = false,
        ?int $code = null,
    ) {
        parent::__construct($message, $code);
    }

}