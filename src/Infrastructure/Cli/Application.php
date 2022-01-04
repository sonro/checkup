<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

final class Application
{
    public function __construct()
    {
    }

    /**
     * Run the application.
     *
     * @param array $arguments
     * @return RunResult
     */
    public function run(array $arguments): RunResult
    {
        return RunResult::Success;
    }
}
