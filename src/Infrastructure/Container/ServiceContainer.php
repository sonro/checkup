<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Container;

use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;

class ServiceContainer
{
    private ?Application $application = null;
    private ?ArgumentParser $argumentParser = null;

    public function __construct()
    {
    }

    public function getApplication(): Application
    {
        if ($this->application === null) {
            $this->application = new Application($this->getArgumentParser());
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
}
