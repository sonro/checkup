<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Exception;

class StoreError extends Exception
{
    const INVALID_JSON_MSG = "Could not decode JSON, syntax error - malformed JSON.";
    const FILE_READ_MSG = "Could not read file: ";
    const DESERIALIZE_MSG = "Could not deserialize, expected class: ";
    const VALIDATE_MSG = "Validation error: ";

    public static function invalidJson(): self
    {
        return new self(self::INVALID_JSON_MSG);
    }

    public static function fileRead(string $path): self
    {
        return new self(self::FILE_READ_MSG.$path);
    }

    public static function desirializeError(string $class): self
    {
        return new self(self::DESERIALIZE_MSG.$class);
    }

    /**
     * @param string[] $errors
     * @return self
     */
    public static function validateError(array $errors): self
    {
        $output = "";
        foreach ($errors as $error) {
            $output .= $error.". ";
        }
        return new self($output);
    }
}
