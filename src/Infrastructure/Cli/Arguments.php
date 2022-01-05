<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

class Arguments
{
    public function __construct(
        public readonly bool $verbose = false,
        public readonly bool $version = false,
        public readonly bool $dryRun = false,
        public readonly bool $help = false,
        public readonly ?string $configFile = null,
        public readonly ?string $stateFile = null,
        public readonly ?string $logFile = null,
    ) {
    }
}
