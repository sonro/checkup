<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Application
{
    const VERSION = 'v0.2.0';
    const HELP = <<<EOD
Usage: checkup [options]

    -h, --help                       Display this help message
    -V, --version                    Display version
    -d, --dry-run                    Run without changing state or sending emails.
    -v, --verbose                    Enable verbose output

    -c, --config <path>              Specify path to config file 
                                     Default: env "CHECKUP_APP_DIR"/config.yml

    -c, --state <path>               Path to state file
                                     Default: env "CHECKUP_APP_DIR"/state.json

    -l, --log <path>                 Path to log file
                                     Default: env "CHECKUP_APP_DIR"/app.log

EOD;

    public function __construct(
        private ArgumentParser $argParser,
        private Logger $logger,
    ) {
    }

    /**
     * Run the application with command line arguments.
     *
     * @param string[] $argArray
     * @return RunResult
     */
    public function runWithArgs(array $argArray): RunResult
    {
        $options = $this->getOptions($argArray);
        if ($options instanceof RunResult) {
            return $options;
        }

        return $this->runWithOptions($options);
    }

    /**
     * Run the application with options.
     *
     * @param Options $options
     * @return RunResult
     */
    public function runWithOptions(Options $options): RunResult
    {
        if ($options->help) {
            $this->printHelp();
            return RunResult::Success;
        }
        if ($options->version) {
            $this->printVersion();
            return RunResult::Success;
        }
        $this->setupLogging($options);

        return RunResult::Success;
    }

    /**
     * Get the options from the command line arguments and environment.
     *
     * @param string[] $argArray
     * @return RunResult|Options
     */
    private function getOptions(array $argArray): RunResult|Options
    {
        try {
            $args = $this->argParser->parse($argArray);
            $options = Options::fromArguments($args);
        } catch (ArgumentsError $e) {
            $this->printHelp();
            $this->printError($e->getMessage());
            return RunResult::ArgumentsError;
        } catch (EnvironmentError $e) {
            $this->printError($e->getMessage());
            return RunResult::EnvironmentError;
        }

        return $options;
    }

    private function printVersion(): void
    {
        echo self::VERSION.PHP_EOL;
    }

    private function printError(string $msg): void
    {
        echo "error: $msg".PHP_EOL;
    }

    private function printHelp(): void
    {
        echo PHP_EOL.self::HELP.PHP_EOL;
    }

    private function setupLogging(Options $options): void
    {
        if ($options->verbose) {
            $level = Logger::DEBUG;
        } elseif ($options->dryRun) {
            $level = Logger::INFO;
        } else {
            $level = Logger::ERROR;
        }
        $this->logger->pushHandler(new StreamHandler($options->logFile, Logger::INFO));
        $this->logger->pushHandler(new StreamHandler("php://stderr", $level));
    }
}
