<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

class Application
{
    public function __construct(private ArgumentParser $argParser)
    {
    }

    /**
     * Run the application.
     *
     * @param string[] $argArray
     * @return RunResult
     */
    public function run(array $argArray): RunResult
    {
        $_args = $this->argParser->parse($argArray);
        return RunResult::Success;
    }
}
