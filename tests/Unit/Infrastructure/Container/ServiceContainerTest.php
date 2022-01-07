<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\ServiceContainer;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Domain\CheckupService;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Container\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $container = $this->createContainer();
        $this->assertInstanceOf(ServiceContainer::class, $container);
    }

    public function test_get_application_fresh(): void
    {
        $this->assertServiceFresh("application", Application::class);
    }

    public function test_get_application_cached(): void
    {
        $this->assertServiceCached("application", Application::class);
    }

    public function test_get_logger_fresh(): void
    {
        $this->assertServiceFresh("logger", Logger::class);
    }

    public function test_get_logger_cached(): void
    {
        $this->assertServiceCached("logger", Logger::class);
    }

    public function test_get_checkup_service_fresh(): void
    {
        $this->assertServiceFresh("checkupService", CheckupService::class);
    }

    public function test_get_checkup_service_cached(): void
    {
        $this->assertServiceCached("checkupService", CheckupService::class);
    }

    private function assertServiceFresh(string $name, string $fqcn): void
    {
        $container = $this->createContainer();
        $service = $this->getServiceFromContainer($name, $container);
        $this->assertInstanceOf($fqcn, $service);
    }

    private function assertServiceCached(string $name, string $fqcn): void
    {
        $container = $this->createContainer();
        $service1 = $this->getServiceFromContainer($name, $container);
        $service2 = $this->getServiceFromContainer($name, $container);
        $this->assertSame($service1, $service2);
    }

    private function createContainer(): ServiceContainer
    {
        return new ServiceContainer();
    }

    private function getServiceFromContainer(
        string $service,
        ServiceContainer $container
    ): Object {
        $method = 'get' . ucfirst($service);
        return $container->$method();
    }
}
