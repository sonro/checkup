<?php

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\RunResult;

class RunResultTest extends TestCase
{
    public function test_is_enum(): void
    {
        $this->assertTrue(enum_exists(RunResult::class));
    }

    public function test_all_cases_exist(): void {
        $expected = [
            RunResult::Success,
            RunResult::ArgumentsError,
        ];
        $this->assertSame($expected, RunResult::cases());
    }
}
