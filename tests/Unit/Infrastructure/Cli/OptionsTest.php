<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Cli;

use PHPUnit\Framework\TestCase;
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
        $dir = getenv(Options::DIR_ENV_NAME);
        $configFile = $dir . '/' . Options::DEFAULT_CONFIG_NAME;
        $stateFile = $dir . '/' . Options::DEFAULT_STATE_NAME;
        $logFile = $dir . '/' . Options::DEFAULT_LOG_NAME;
        $this->assertFalse($options->verbose);
        $this->assertFalse($options->version);
        $this->assertFalse($options->dryRun);
        $this->assertFalse($options->help);
        $this->assertSame($configFile, $options->configFile);
        $this->assertSame($stateFile, $options->stateFile);
        $this->assertSame($logFile, $options->logFile);
    }
}