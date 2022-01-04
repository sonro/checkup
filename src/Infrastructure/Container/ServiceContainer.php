<?php

namespace Sonro\Checkup\Infrastructure\Container;

use Sonro\Checkup\Infrastructure\Cli\Application;

final class ServiceContainer
{
    private ?Application $application = null;

    public function __construct()
    {
    }

    public function getApplication(): Application
    {
        if ($this->application === null) {
            $this->application = new Application();
        }

        return $this->application;
    }
}
