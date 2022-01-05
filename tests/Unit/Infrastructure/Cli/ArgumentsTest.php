<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\Arguments;

class ArgumentsTest extends TestCase
{
    public function test_defaults(): void
    {
        $args = new Arguments();
        $this->assertFalse($args->verbose);
        $this->assertFalse($args->dryRun);
        $this->assertFalse($args->help);
        $this->assertNull($args->configFile);
        $this->assertNull($args->stateFile);
    }

    public function test_set_all(): void
    {
        $verbose = true;
        $dryRun = true;
        $help = true;
        $configFile = 'test.yml';
        $stateFile = 'test.json';

        $args = new Arguments($verbose, $dryRun, $help, $configFile, $stateFile);

        $this->assertTrue($args->verbose);
        $this->assertTrue($args->dryRun);
        $this->assertTrue($args->help);
        $this->assertSame($configFile, $args->configFile);
        $this->assertSame($stateFile, $args->stateFile);
    }
}
