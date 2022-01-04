<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\ServiceContainer;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Container\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $container = new ServiceContainer();
        $this->assertInstanceOf(ServiceContainer::class, $container);
    }

    public function test_get_application_fresh(): void
    {
        $container = new ServiceContainer();
        $app = $container->getApplication();
        $this->assertInstanceOf(Application::class, $app);
    }

    public function test_get_application_cached(): void
    {
        $container = new ServiceContainer();
        $app1 = $container->getApplication();
        $app2 = $container->getApplication();
        $this->assertSame($app1, $app2);
    }

}
