<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Container;

use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Monolog\Logger;

class ServiceContainer
{
    private ?Application $application = null;
    private ?ArgumentParser $argumentParser = null;
    private ?Logger $logger = null;

    public function __construct(
    ) {
    }

    public function getApplication(): Application
    {
        if ($this->application === null) {
            $this->application = new Application(
                $this->getArgumentParser(),
                $this->getLogger(),
            );
        }

        return $this->application;
    }

    public function getArgumentParser(): ArgumentParser
    {
        if ($this->argumentParser === null) {
            $this->argumentParser = new ArgumentParser();
        }

        return $this->argumentParser;
    }

    public function getLogger(): Logger
    {
        if ($this->logger === null) {
            $this->logger = new Logger("app");
        }

        return $this->logger;
    }
}
