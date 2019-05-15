<?php

namespace App;

class UrlListBuilder
{
    public static function buildFromFile(string $filename): array
    {
        $data = file_get_contents($filename);
        if ($data === false) {
            throw new \Exception("Unable to read file: $filename");
        }

        return array_filter(explode("\n", $data));
    }
}
