<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Container;

use Monolog\Logger;
use Sonro\Checkup\Domain\CheckupService;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Persistance\Serializer;

class ServiceContainer
{
    private ?Application $application = null;
    private ?ArgumentParser $argumentParser = null;
    private ?Logger $logger = null;
    private ?CheckupService $checkupService = null;
    private ?Serializer $serializer = null;

    public function __construct(
    ) {
    }

    public function getApplication(): Application
    {
        if ($this->application === null) {
            $this->application = new Application(
                $this->getArgumentParser(),
                $this->getLogger(),
                $this->getCheckupService(),
                $this->getSerializer(),
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

    public function getCheckupService(): CheckupService
    {
        if ($this->checkupService === null) {
            $this->checkupService = new CheckupService();
        }

        return $this->checkupService;
    }

    public function getSerializer(): Serializer
    {
        if ($this->serializer === null) {
            $this->serializer = new Serializer();
        }

        return $this->serializer;
    }
}
