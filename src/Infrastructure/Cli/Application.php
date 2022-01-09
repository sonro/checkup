<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Sonro\Checkup\Domain\CheckupService;
use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Domain\Model\State;
use Sonro\Checkup\Infrastructure\Persistance\FileConfigStore;
use Sonro\Checkup\Infrastructure\Persistance\FileStateStore;
use Sonro\Checkup\Infrastructure\Persistance\Serializer;

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

    private ?FileConfigStore $configStore = null;
    private ?FileStateStore $stateStore = null;

    public function __construct(
        private ArgumentParser $argParser,
        private Logger $logger,
        private CheckupService $checkup,
        private Serializer $serializer,
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

        $this->setup($options);

        try {
            $config = $this->loadConfig($options);
            $state = $this->loadState($options);
            $this->checkup->execute($config, $state);
            $this->saveState($state, $options);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return RunResult::Failure;
        }

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

    private function setup(Options $options): void
    {
        $this->setupLogging($options);
        $this->setupStores($options);
    }

    private function loadConfig(Options $options): Config
    {
        if ($this->configStore === null) {
            throw new Exception('Unable to configure config loader');
        }

        $this->logger->debug("Loading configuration from {$options->configFile}");
        $config = $this->configStore->load();
        $this->logger->info("Configuration loaded");

        return $config;
    }

    private function loadState(Options $options): State
    {
        if ($this->stateStore === null) {
            throw new Exception('Unable to configure state loader/saver');
        }

        $this->logger->debug("Loading state from {$options->stateFile}");

        if ($options->dryRun) {
            $state = new State();
            $this->logger->info("Dummy state loaded");
        } else {
            $state = $this->stateStore->load();
            $this->logger->info("State loaded");
        }

        return $state;
    }

    private function saveState(State $state, Options $options): void
    {
        if ($this->stateStore === null) {
            throw new Exception('Unable to configure state loader/saver');
        }

        if (!$options->dryRun) {
            $this->logger->debug("Saving state to {$options->stateFile}");
            $this->stateStore->store($state);
            $this->logger->info("State saved");
        }
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
        $this->logger->debug("Verbose mode enabled");
    }

    private function setupStores(Options $options): void
    {
        $this->configStore = new FileConfigStore($options->configFile, $this->serializer);
        $this->stateStore = new FileStateStore($options->stateFile);
    }
}
