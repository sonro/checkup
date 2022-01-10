<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

class Site
{
    public bool $adminUpdated = true;

    public function __construct(public readonly string $url)
    {
    }
}
