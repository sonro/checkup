<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

enum RunResult
{
    case Success;
    case ArgumentsError;
    case EnvironmentError;
    case Failure;
}
