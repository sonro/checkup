<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\Stub;
use Sonro\Checkup\Infrastructure\Cli\Application;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Cli\Arguments;
use Sonro\Checkup\Infrastructure\Cli\ArgumentsError;
use Sonro\Checkup\Infrastructure\Cli\RunResult;

class ApplicationTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $app = $this->createApplication();
        $this->assertInstanceOf(Application::class, $app);
    }

    public function 
        test_run_with_args_with_args_returns_run_with_args_result()
        : void
    {
        $app = $this->createApplication();
        $result = $app->runWithArgs([]);
        $this->assertInstanceOf(RunResult::class, $result);
    }

    public function test_invalid_args_results_in_arguments_error()
    {
        $parser = $this->createArgumentParserStub();
        $parser->method('parse')->willThrowException(new ArgumentsError());
        $app = $this->createApplication($parser);
        $result = $app->runWithArgs(["invalid-arg"]);
        $this->assertSame(RunResult::ArgumentsError, $result);
    }

    private function createApplication(?Stub $parser = null): Application
    {
        if ($parser === null) {
            $parser = $this->createArgumentParserStub();
        }
        $logger = $this->createStub(Logger::class);
        return new Application($parser, $logger);
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
