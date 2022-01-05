<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\Arguments;
use Sonro\Checkup\Infrastructure\Cli\ArgumentParser;
use Sonro\Checkup\Infrastructure\Cli\ArgumentsError;

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
        $this->assertSame($expected->version, $actual->version);
        $this->assertSame($expected->dryRun, $actual->dryRun);
        $this->assertSame($expected->help, $actual->help);
        $this->assertSame($expected->configFile, $actual->configFile);
        $this->assertSame($expected->stateFile, $actual->stateFile);
        $this->assertSame($expected->logFile, $actual->logFile);
    }

    public function test_parse_verbose_short(): void
    {
        $this->assertParseOneFlag('-v', 'verbose');
    }

    public function test_parse_verbose_long(): void
    {
        $this->assertParseOneFlag('--verbose', 'verbose');
    }

    public function test_parse_version_short(): void
    {
        $this->assertParseOneFlag('-V', 'version');
    }

    public function test_parse_version_long(): void
    {
        $this->assertParseOneFlag('--version', 'version');
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

    public function test_parse_log_file_short(): void
    {
        $this->assertParseOneFile('-l', 'logFile');
    }

    public function test_parse_log_file_long(): void
    {
        $this->assertParseOneFile('--log', 'logFile');
    }

    public function test_parse_all_flags_together(): void
    {
        $this->assertParseMulti(
            ['-vVdh'],
            $this->allFlagsArrayTrue(),
        );
    }

    public function test_parse_all_short_flags_seperated(): void
    {
        $this->assertParseMulti(
            ['-v', '-V', '-d', '-h'],
            $this->allFlagsArrayTrue(),
        );
    }

    public function test_parse_all_long_flags(): void
    {
        $this->assertParseMulti(
            ['--verbose', '--version', '--dry-run', '--help'],
            $this->allFlagsArrayTrue(),
        );
    }

    public function test_parse_all_files_short(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $logFile = 'test.log';
        $this->assertParseMulti(
            ['-c', $configFile, '-s', $stateFile, '-l', $logFile],
            $this->allFilesArray($configFile, $stateFile, $logFile),
        );
    }

    public function test_parse_all_fiels_long(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $logFile = 'test.log';
        $this->assertParseMulti(
            ['--config', $configFile, '--state', $stateFile, '--log', $logFile],
            $this->allFilesArray($configFile, $stateFile, $logFile),
        );
    }

    public function test_parse_all_args_short(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $logFile = 'test.log';
        $this->assertParseMulti(
            [
                '-c',
                $configFile, 
                '-s',
                $stateFile,
                '-l',
                $logFile,
                '-v',
                '-V',
                '-d', 
                '-h',
            ],
            $this->allArgs($configFile, $stateFile, $logFile),
        );
    }

    public function test_parse_all_args_long(): void
    {
        $configFile = 'test.yml';
        $stateFile = 'test.json';
        $logFile = 'test.log';
        $this->assertParseMulti(
            [
                '--config',
                $configFile,
                '--state',
                $stateFile,
                '--log',
                $logFile,
                '--verbose',
                '--version',
                '--dry-run',
                '--help'
            ],
            $this->allArgs($configFile, $stateFile, $logFile),
        );
    }

    public function test_parse_invalid_short_flag(): void
    {
        $this->assertArgumentsError(['-x'], 'Invalid argument: -x');
    }

    public function test_parse_invalid_long_flag(): void
    {
        $this->assertArgumentsError(['--xtra'], 'Invalid argument: --xtra');
    }

    public function test_parse_invalid_short_file(): void
    {
        $this->assertArgumentsError(['-s'], 'state flag [--state] requires a value');
    }

    public function test_parse_invalid_long_file(): void
    {
        $this->assertArgumentsError(['config flag [--config] requires a value'], '--config');
    }

    public function test_parse_invalid_arg(): void
    {
        $this->assertArgumentsError(['test'], 'Invalid argument: test');
    }

    public function test_parse_mixed_valid_and_invalid_args(): void
    {
        $this->assertArgumentsError(['-v', 'test'], 'Invalid argument: test');
    }

    public function test_parse_mixed_valid_and_invalid_flags_together(): void
    {
        $this->assertArgumentsError(['-vxd'], 'Invalid argument: -x');
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

    private function assertArgumentsError(array $args, string $msg): void
    {
        $this->expectException(ArgumentsError::class);
        $this->expectExceptionMessage($msg);
        $parser = new ArgumentParser();
        $parser->parse($args);
    }

    private function allFlagsArrayTrue(): array
    {
        return [
            'verbose' => true,
            'version' => true,
            'dryRun' => true,
            'help' => true,
        ];
    }

    private function allFilesArray(
        string $configFile = 'test.yml',
        string $stateFile = 'test.json',
        string $logFile = 'test.log',
    ): array {
        return [
            'configFile' => $configFile,
            'stateFile' => $stateFile,
            'logFile' => $logFile,
        ];
    }

    private function allArgs(
        string $configFile = 'test.yml',
        string $stateFile = 'test.json',
        string $logFile = 'test.log',
    ): array {
        return array_merge(
            $this->allFlagsArrayTrue(),
            $this->allFilesArray($configFile, $stateFile, $logFile),
        );
    }
}
