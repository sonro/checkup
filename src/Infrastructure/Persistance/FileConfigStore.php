<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Domain\Model\ConfigStoreInterface;

class FileConfigStore implements ConfigStoreInterface
{
    public function __construct(
        private string $path,
    ) {
    }

    public function load(): Config
    {
        return new Config();
    }
}