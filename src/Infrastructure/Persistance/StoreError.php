<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Exception;

class StoreError extends Exception
{
    const CODE_UNKNOWN = 0;
    const CODE_JSON = 1;
    const CODE_FILE_READ = 2;
    const CODE_FILE_WRITE = 3;
    const CODE_FILE_PARSE = 4;
    const CODE_VALIDATE= 5;
    const CODE_DESERIALIZE = 6;

    const INVALID_JSON_MSG = "Could not decode JSON, syntax error - malformed JSON.";
    const FILE_READ_MSG = "Could not read file: ";
    const FILE_WRITE_MSG = "Could not write file: ";
    const FILE_PARSE_MSG = "Could not parse file: ";
    const DESERIALIZE_MSG = "Could not deserialize, expected class: ";
    const VALIDATE_MSG = "Validation error: ";

    public static function invalidJson(): self
    {
        return new self(self::INVALID_JSON_MSG, self::CODE_JSON);
    }

    public static function fileRead(string $path): self
    {
        return new self(self::FILE_READ_MSG.$path, self::CODE_FILE_READ);
    }

    public static function desirializeError(string $class): self
    {
        return new self(self::DESERIALIZE_MSG.$class, self::CODE_DESERIALIZE);
    }

    public static function fileParse(string $path): self
    {
        return new self(self::FILE_PARSE_MSG.$path, self::CODE_FILE_PARSE);
    }

    public static function fileWrite(string $path): self
    {
        return new self(self::FILE_WRITE_MSG.$path, self::CODE_FILE_WRITE);
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
        return new self($output, self::CODE_VALIDATE);
    }
}
