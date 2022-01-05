<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Cli\RunResult;

class ApplicationTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $app = $this->createApplication();
        $this->assertInstanceOf(Application::class, $app);
    }

    public function test_run_returns_run_result(): void
    {
        $app = $this->createApplication();
        $result = $app->run([]);
        $this->assertInstanceOf(RunResult::class, $result);
    }

    private function createApplication(): Application
    {
        $argParser = $this->createStub(ArgumentParser::class);
        return new Application($argParser);
    }
}
