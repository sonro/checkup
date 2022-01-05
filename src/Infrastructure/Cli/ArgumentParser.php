<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

/**
 * Parses command line arguments into an Arguments object.
 */
class ArgumentParser
{
    // List of all possible arguments.
    private const VERBOSE_SHORT = 'v';
    private const VERBOSE_LONG = 'verbose';
    private const DRY_RUN_SHORT = 'd';
    private const DRY_RUN_LONG = 'dry-run';
    private const HELP_SHORT = 'h';
    private const HELP_LONG = 'help';
    private const CONFIG_SHORT = 'c';
    private const CONFIG_LONG = 'config';
    private const STATE_SHORT = 's';
    private const STATE_LONG = 'state';

    /**
     * @var array<string, bool>
     */
    private array $flags;

    /**
     * @var array<string, ?string>
     */
    private array $files;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * Parse an array of command line argument strings into an Arguments object.
     *
     * @param string[] $argArray
     * 
     * @return Arguments
     * 
     * @throws ArgumentsError if the arguments are invalid
     */
    public function parse(array $argArray): Arguments
    {
        $argc = count($argArray);

        for ($i = 0; $i < $argc; $i++) {
            // '-' prefix
            $short = $argArray[$i][0] === '-';

            if ($short) {
                // '--' prefix
                $long = $argArray[$i][1] === '-';
                // next arg or null
                $next = $argArray[$i + 1] ?? null;

                if ($long) {
                    $consumedNext = $this->parseLong($argArray[$i], $next);
                } else {
                    $consumedNext = $this->parseShort($argArray[$i], $next);
                }
                if ($consumedNext) {
                    $i++;
                }
            } else {
                $this->cleanUpAndThrow("Invalid argument: {$argArray[$i]}");
            }

        }

        // this should reset the parser's state
        return $this->argumentsFromData();
    }

    /**
     * Reset the parser to its initial state.
     *
     * @return void
     */
    private function reset(): void
    {
        $this->flags = [
            self::VERBOSE_LONG => false,
            self::DRY_RUN_LONG => false,
            self::HELP_LONG => false,
        ];
        $this->files = [
            self::CONFIG_LONG => null,
            self::STATE_LONG => null,
        ];
    }


    /**
     * Build an Arguments object from the current state of the parser.
     * 
     * Resets the parser's state.
     *
     * @return Arguments
     */
    private function argumentsFromData(): Arguments
    {
        // extract and reset parser's state in case there is an error while
        // building the Arguments object
        $verbose = $this->flags[self::VERBOSE_LONG];
        $dryRun = $this->flags[self::DRY_RUN_LONG];
        $help = $this->flags[self::HELP_LONG];
        $configFile = $this->files[self::CONFIG_LONG];
        $stateFile = $this->files[self::STATE_LONG];

        $this->reset();

        return new Arguments(
            $verbose,
            $dryRun,
            $help,
            $configFile,
            $stateFile
        );
    }

    /**
     * Parse a short (-v) argument.
     * 
     * @param string $arg
     * @param string|null $nextArg if the $arg needs a value, $nextArg is used
     * 
     * @return boolean true if $nextArg was used
     * 
     * @throws ArgumentsError if the argument is invalid or if a value is 
     * needed and missing
     */
    private function parseShort(string $arg, ?string $nextArg): bool
    {
        // arg without '-'
        $match = substr($arg, 1);

        if ($match === self::CONFIG_SHORT) {
            $this->setFile(self::CONFIG_LONG, $nextArg);
            // used the next argument
            return true;
        }

        if ($match === self::STATE_SHORT) {
            $this->setFile(self::STATE_LONG, $nextArg);
            // used the next argument
            return true;
        }

        $chars = str_split($match);

        foreach ($chars as $char) {
            match ($char) {
                self::VERBOSE_SHORT => $this->checkAndSet(self::VERBOSE_LONG),

                self::DRY_RUN_SHORT => $this->checkAndSet(self::DRY_RUN_LONG),

                self::HELP_SHORT => $this->checkAndSet(self::HELP_LONG),

                default => $this->cleanUpAndThrow("Invalid argument: -{$char}"),
            };
        }
        
        // NOT used the next argument
        return false;
    }

    /**
     * Parse a long (--verbose) argument.
     * 
     * @param string $arg
     * @param string|null $nextArg if the $arg needs a value, $nextArg is used
     * 
     * @return boolean true if $nextArg was used
     * 
     * @throws ArgumentsError if the argument is invalid, or if a value
     * is needed and missing
     */
    private function parseLong(string $arg, ?string $nextArg): bool
    {
        // arg without '--'
        $match = substr($arg, 2);

        if ($match == self::CONFIG_LONG) {
            $this->setFile(self::CONFIG_LONG, $nextArg);
            // used the next argument
            return true;
        }

        if ($match == self::STATE_LONG) {
            $this->setFile(self::STATE_LONG, $nextArg);
            // used the next argument
            return true;
        }

        match ($match) {
            self::VERBOSE_LONG => $this->checkAndSet(self::VERBOSE_LONG),

            self::DRY_RUN_LONG => $this->checkAndSet(self::DRY_RUN_LONG),

            self::HELP_LONG => $this->checkAndSet(self::HELP_LONG),

            default => $this->cleanUpAndThrow("Invalid argument: --{$match}"),
        };

        // NOT used the next argument
        return false;
    }

    /**
     * Set a file argument.
     * 
     * @param string $type filetype: either 'config' or 'state'
     * @param string|null $path 
     * 
     * @return void
     * 
     * @throws ArgumentsError if the argument is already set, or if the
     * path is null
     */
    private function setFile(string $type, ?string $path): void
    {
        $fileAlreadySet = $this->files[$type] !== null;

        if ($fileAlreadySet) {
            $this->cleanUpAndThrow("$type can only be set once");
        }

        if ($path === null) {
            $this->cleanUpAndThrow("$type flag [--$type] requires a value");
        }

        $this->files[$type] = $path;
    }

    /**
     * Set a flag if it is not already set.
     * 
     * @param string $flag
     * 
     * @return void
     * 
     * @throws ArgumentsError if the argument is already set
     */
    private function checkAndSet(string $flag): void
    {
        if ($this->flags[$flag]) {
            $this->cleanUpAndThrow("Only one of [--verbose, --dry-run, --help] can be set.");
        }

        $this->flags[$flag] = true;
    }

    /**
     * Restore the state of the parser and throw an ArgumentsError.
     *
     * @param string $msg
     * @return never
     * @throws ArgumentsError always
     */
    private function cleanUpAndThrow(string $msg): never
    {
        $this->reset();
        throw new ArgumentsError($msg);
    }
}
