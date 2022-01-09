<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\Stub;
use Sonro\Checkup\Domain\CheckupService;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Cli\Arguments;
use Sonro\Checkup\Infrastructure\Cli\ArgumentsError;
use Sonro\Checkup\Infrastructure\Cli\EnvironmentError;
use Sonro\Checkup\Infrastructure\Cli\Options;
use Sonro\Checkup\Infrastructure\Cli\RunResult;
use Sonro\Checkup\Infrastructure\Persistance\Serializer;

class ApplicationTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $app = $this->createApplication();
        $this->assertInstanceOf(Application::class, $app);
    }

    public function test_run_with_args_returns_run_result() : void
    {
        $app = $this->createApplication();
        $result = $app->runWithArgs([]);
        $this->assertInstanceOf(RunResult::class, $result);
    }

    public function test_run_with_invalid_args_returns_arguments_error(): void
    {
        $parser = $this->createArgumentParserStub();
        $parser->method('parse')->willThrowException(new ArgumentsError());
        $app = $this->createApplication($parser);
        $result = $app->runWithArgs(["invalid-arg"]);
        $this->assertSame(RunResult::ArgumentsError, $result);
    }

    public function test_invalid_env_returns_env_error(): void
    {
        $parser = $this->createArgumentParserStub();
        $parser->method('parse')->willThrowException(new EnvironmentError());
        $app = $this->createApplication($parser);
        $result = $app->runWithArgs([]);
        $this->assertSame(RunResult::EnvironmentError, $result);
    }

    public function test_run_with_options_returns_run_result(): void
    {
        $app = $this->createApplication();
        $options = Options::default();
        $result = $app->runWithOptions($options);
        $this->assertInstanceOf(RunResult::class, $result);
    }

    private function createApplication(?Stub $parser = null): Application
    {
        if ($parser === null) {
            $parser = $this->createArgumentParserStub();
        }
        $logger = $this->createStub(Logger::class);
        $checkup = $this->createStub(CheckupService::class);
        $serializer = $this->createStub(Serializer::class);
        return new Application($parser, $logger, $checkup, $serializer);
    }

    /**
     * @return ArgumentParser|Stub
     */
    private function createArgumentParserStub(): ArgumentParser
    {
        /** @var Stub|ArgumentParser */
        $stub = $this->createStub(ArgumentParser::class);
        $stub->method('parse')->willReturn(new Arguments());

        return $stub;
    }
}
