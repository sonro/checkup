<?php

namespace Sonro\Checkup\Infrastructure\Cli;

enum RunResult
{
    case Success;
    case ArgumentsError;
}