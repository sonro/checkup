<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Infrastructure\Cli\Arguments;
use Sonro\Checkup\Infrastructure\Cli\EnvironmentError;
use Sonro\Checkup\Infrastructure\Cli\Options;

class OptionsTest extends TestCase
{
    public function test_const_dir_env_name(): void
    {
        $this->assertSame('CHECKUP_APP_DIR', Options::DIR_ENV_NAME); 
    }

    public function test_const_default_config_name(): void
    {
        $this->assertSame('config.yml', Options::DEFAULT_CONFIG_NAME);
    }

    public function test_const_default_state_name(): void
    {
        $this->assertSame('state.json', Options::DEFAULT_STATE_NAME);
    }

    public function test_const_default_log_name(): void
    {
        $this->assertSame('app.log', Options::DEFAULT_LOG_NAME);
    }

    public function test_default(): void
    {
        $options = Options::default();
        
        $this->assertFalse($options->verbose);
        $this->assertFalse($options->version);
        $this->assertFalse($options->dryRun);
        $this->assertFalse($options->help);

        $this->assertOptionsDefaultFiles($options);
    }

    public function test_from_arguments(): void
    {
        $args = new Arguments(
            verbose: true,
            version: true,
            dryRun: true,
            help: true,
            configFile: 'config.test',
            stateFile: 'state.test',
            logFile: 'log.test'
        );

        $options = Options::fromArguments($args);

        $this->assertSame($args->verbose, $options->verbose);
        $this->assertSame($args->version, $options->version);
        $this->assertSame($args->dryRun, $options->dryRun);
        $this->assertSame($args->help, $options->help);

        $this->assertOptionsSameFiles(
            $options,
            $args->configFile,
            $args->stateFile,
            $args->logFile
        );
    }

    public function test_from_arguments_with_default_files(): void
    {
        $args = new Arguments(
            verbose: true,
            version: true,
            dryRun: true,
            help: true,
            configFile: null,
            stateFile: null,
            logFile: null
        );

        $options = Options::fromArguments($args);
        $this->assertOptionsDefaultFiles($options);
    }

    private function assertOptionsDefaultFiles(Options $options): void
    {
        $dir = getenv(Options::DIR_ENV_NAME);
        $configFile = $this->joinDirToFile($dir, Options::DEFAULT_CONFIG_NAME);
        $stateFile = $this->joinDirToFile($dir, Options::DEFAULT_STATE_NAME);
        $logFile = $this->joinDirToFile($dir, Options::DEFAULT_LOG_NAME);

        $this->assertOptionsSameFiles($options, $configFile, $stateFile, $logFile);
    }

    private function assertOptionsSameFiles(
        Options $options,
        string $configFile, 
        string $stateFile,
        string $logFile,
    ): void {
        $this->assertSame($configFile, $options->configFile);
        $this->assertSame($stateFile, $options->stateFile);
        $this->assertSame($logFile, $options->logFile);
    }

    private function joinDirToFile(string $dir, string $file): string
    {
        return $dir.'/'.$file;
    }
}