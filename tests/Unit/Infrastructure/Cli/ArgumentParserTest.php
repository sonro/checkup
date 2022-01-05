<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use Exception;
use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Cli\Arguments;

class ArgumentParserTest extends TestCase
{
    public function test_it_can_be_instantiated(): void
    {
        $parser = new ArgumentParser();
        $this->assertInstanceOf(ArgumentParser::class, $parser);
    }

    public function test_parse_returns_arguments(): void
    {
        $parser = new ArgumentParser();
        $args = $parser->parse([]);
        $this->assertInstanceOf(Arguments::class, $args);
    }

    public function test_parse_empty_args_returns_default(): void
    {
        $parser = new ArgumentParser();
        $expected = new Arguments();
        $actual = $parser->parse([]);
        $this->assertSame($expected->verbose, $actual->verbose);
        $this->assertSame($expected->dryRun, $actual->dryRun);
        $this->assertSame($expected->help, $actual->help);
        $this->assertSame($expected->configFile, $actual->configFile);
        $this->assertSame($expected->stateFile, $actual->stateFile);
    }

    public function test_parse_verbose_short(): void
    {
        $this->assertParseOneFlag('-v', 'verbose');
    }

    public function test_parse_verbose_long(): void
    {
        $this->assertParseOneFlag('--verbose', 'verbose');
    }

    public function test_parse_dry_run_short(): void
    {
        $this->assertParseOneFlag('-d', 'dryRun');
    }

    public function test_parse_dry_run_long(): void
    {
        $this->assertParseOneFlag('--dry-run', 'dryRun');
    }

    public function test_parse_help_short(): void
    {
        $this->assertParseOneFlag('-h', 'help');
    }

    public function test_parse_help_long(): void
    {
        $this->assertParseOneFlag('--help', 'help');
    }

    public function test_parse_config_file_short(): void
    {
        $this->assertParseOneFile('-c', 'configFile');
    }

    public function test_parse_config_file_long(): void
    {
        $this->assertParseOneFile('--config', 'configFile');
    }

    public function test_parse_state_file_short(): void
    {
        $this->assertParseOneFile('-s', 'stateFile');
    }

    public function test_parse_state_file_long(): void
    {
        $this->assertParseOneFile('--state', 'stateFile');
    }

    public function test_parse_all_flags_together(): void
    {
        $this->assertParseMulti(
            ['-vdh'],
            ['verbose' => true, 'dryRun' => true, 'help' => true]
        );
    }

    public function test_parse_all_short_flags_seperated(): void
    {
        $this->assertParseMulti(
            ['-v', '-d', '-h'],
            ['verbose' => true, 'dryRun' => true, 'help' => true]
        );
    }

    public function test_parse_all_long_flags(): void
    {
        $this->assertParseMulti(
            ['--verbose', '--dry-run', '--help'],
            ['verbose' => true, 'dryRun' => true, 'help' => true]
        );
    }

    public function test_parse_all_files_short(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $this->assertParseMulti(
            ['-c', $configFile, '-s', $stateFile],
            ['configFile' => $configFile, 'stateFile' => $stateFile]
        );
    }

    public function test_parse_all_fiels_long(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $this->assertParseMulti(
            ['--config', $configFile, '--state', $stateFile],
            ['configFile' => $configFile, 'stateFile' => $stateFile]
        );
    }

    public function test_parse_all_args_short(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $this->assertParseMulti(
            ['-c', $configFile, '-s', $stateFile, '-v', '-d', '-h'],
            [
                'configFile' => $configFile,
                'stateFile' => $stateFile,
                'verbose' => true,
                'dryRun' => true,
                'help' => true
            ]
        );
    }

    public function test_parse_all_args_long(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $this->assertParseMulti(
            [
                '--config',
                $configFile,
                '--state',
                $stateFile,
                '--verbose',
                '--dry-run',
                '--help'
            ],
            [
                'configFile' => $configFile,
                'stateFile' => $stateFile,
                'verbose' => true,
                'dryRun' => true,
                'help' => true
            ]
        );
    }

    public function test_parse_invalid_short_flag(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid argument: -x');
        $parser = new ArgumentParser();
        $parser->parse(['-x']);
    }

    public function test_parse_invalid_long_flag(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid argument: --xtra');
        $parser = new ArgumentParser();
        $parser->parse(['--xtra']);
    }

    public function test_parse_invalid_short_file(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('state flag [--state] requires a value');
        $parser = new ArgumentParser();
        $parser->parse(['-s']);
    }

    public function test_parse_invalid_long_file(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('config flag [--config] requires a value');
        $parser = new ArgumentParser();
        $parser->parse(['--config']);
    }

    public function test_parse_invalid_arg(): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid argument: test');
        $parser = new ArgumentParser();
        $parser->parse(['test']);
    }

    public function test_parse_mixed_valid_and_invalid_args(): void {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid argument: test');
        $parser = new ArgumentParser();
        $parser->parse(['-v', 'test']);
    }

    private function assertParseOneFlag(string $arg, string $propName): void
    {
        $this->assertParseMulti([$arg], [$propName => true]);
    }

    private function assertParseOneFile(string $arg, string $propName): void
    {
        $file = 'test.yml';
        $this->assertParseMulti([$arg, $file], [$propName => $file]);
    }

    private function assertParseMulti(array $args, array $propNames): void
    {
        $parser = new ArgumentParser();
        $args = $parser->parse($args);
        foreach ($propNames as $key => $value) {
            $this->assertSame($args->$key, $value);
        }
    }
}
