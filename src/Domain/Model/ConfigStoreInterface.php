<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

interface ConfigStoreInterface
{
    public function load(): Config;
}
