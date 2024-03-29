<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

/**
 * Program options.
 */
class Options
{
    const DIR_ENV_NAME = 'CHECKUP_APP_DIR';
    const DEFAULT_CONFIG_NAME = 'config.yml';
    const DEFAULT_STATE_NAME = 'state.json';
    const DEFAULT_LOG_NAME = 'app.log';

    public function __construct(
        public readonly bool $verbose,
        public readonly bool $version,
        public readonly bool $dryRun,
        public readonly bool $help,
        public readonly string $configFile,
        public readonly string $stateFile,
        public readonly string $logFile,
    ) {
    }

    /**
     * The default options for the program.
     * 
     * None of the flags are set.
     * The file paths are all in the CHECKUP_APP_DIR environment variable.
     *
     * @return self
     * @throws EnvironmentError if the CHECKUP_APP_DIR environment 
     * variable is not set.
     */
    public static function default(): self
    {
        return new self(
            false,
            false,
            false,
            false,
            self::envFile(self::DEFAULT_CONFIG_NAME),
            self::envFile(self::DEFAULT_STATE_NAME),
            self::envFile(self::DEFAULT_LOG_NAME),
        );
    }

    /**
     * Construct from an Arguments object.
     * 
     * If the Arguments object lacks a file path for a file,
     * the default file path is used. This uses the CHECKUP_APP_DIR
     * environment variable.
     *
     * @param Arguments $args
     * @return self
     * @throws EnvironmentError if the CHECKUP_APP_DIR environment
     * variable is needed and not set.
     */
    public static function fromArguments(Arguments $args): self
    {
        $configFile = self::filePathOrEnv(
            $args->configFile,
            self::DEFAULT_CONFIG_NAME
        );
        $stateFile = self::filePathOrEnv(
            $args->stateFile,
            self::DEFAULT_STATE_NAME
        );
        $logFile = self::filePathOrEnv(
            $args->logFile,
            self::DEFAULT_LOG_NAME
        );

        return new self(
            $args->verbose,
            $args->version,
            $args->dryRun,
            $args->help,
            $configFile,
            $stateFile,
            $logFile,
        );
    }

    private static function filePathOrEnv(?string $path, string $name): string
    {
        if ($path !== null) {
            return $path;
        }

        return self::envFile($name);
    }

    private static function envFile(string $name): string
    {
        $directory = getenv(self::DIR_ENV_NAME);
        if ($directory === false) {
            throw new EnvironmentError(
                'Environment variable '.self::DIR_ENV_NAME.' is not set'
            );
        }

        return $directory.'/'.$name;
    }
}
