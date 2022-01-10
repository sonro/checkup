<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain;

use Exception;

class CheckupError extends Exception
{
    const CODE_NO_INTERNET_CONNECTION = 1;

    const MSG_NO_INTERNET_CONNECTION = 'No reliable internet connection: ';

    public static function noInternetConnection(string $url): self
    {
        return new self(
            self::MSG_NO_INTERNET_CONNECTION. $url, 
            self::CODE_NO_INTERNET_CONNECTION,
        );
    }
}