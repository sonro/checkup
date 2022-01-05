<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Cli;

use Exception;

class ArgumentParser
{
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
     * @return Arguments
     */
    public function parse(array $argArray): Arguments
    {
        $argc = count($argArray);

        for ($i = 0; $i < $argc; $i++) {
            $short = $argArray[$i][0] === '-';

            if ($short) {
                $long = $argArray[$i][1] === '-';
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

        $arguments = $this->argumentsFromData();
        $this->reset();

        return $arguments;
    }

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

    private function argumentsFromData(): Arguments
    {
        return new Arguments(
            $this->flags[self::VERBOSE_LONG],
            $this->flags[self::DRY_RUN_LONG],
            $this->flags[self::HELP_LONG],
            $this->files[self::CONFIG_LONG],
            $this->files[self::STATE_LONG],
        );
    }

    private function parseShort(string $arg, ?string $nextArg): bool
    {
        if ($arg === "-".self::CONFIG_SHORT) {
            $this->setFile(self::CONFIG_LONG, $nextArg);
            return true;
        }

        if ($arg === "-".self::STATE_SHORT) {
            $this->setFile(self::STATE_LONG, $nextArg);
            return true;
        }

        $set = false;

        if (str_contains($arg, self::VERBOSE_SHORT)) {
            $this->checkAndSet(self::VERBOSE_LONG);
            $set = true;
        }

        if (str_contains($arg, self::DRY_RUN_SHORT)) {
            $this->checkAndSet(self::DRY_RUN_LONG);
            $set = true;
        }

        if (str_contains($arg, self::HELP_SHORT)) {
            $this->checkAndSet(self::HELP_LONG);
            $set = true;
        }

        if (!$set) {
            $this->cleanUpAndThrow("Invalid argument: {$arg}");
        }
        
        return false;
    }

    private function parseLong(string $arg, ?string $nextArg): bool
    {
        $match = substr($arg, 2);

        if ($match == self::CONFIG_LONG) {
            $this->setFile(self::CONFIG_LONG, $nextArg); 
            return true;
        }

        if ($match == self::STATE_LONG) {
            $this->setFile(self::STATE_LONG, $nextArg);
            return true;
        }

        if ($match == self::VERBOSE_LONG) {
            $this->checkAndSet(self::VERBOSE_LONG);
            return false;
        }

        if ($match == self::DRY_RUN_LONG) {
           $this->checkAndSet(self::DRY_RUN_LONG);
           return false;
        }

        if ($match == self::HELP_LONG) {
            $this->checkAndSet(self::HELP_LONG);
            return false;
        }

        $this->cleanUpAndThrow("Invalid argument: $arg");
    }

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

    private function checkAndSet(string $flag): void
    {
        if ($this->flags[$flag]) {
            $this->cleanUpAndThrow("Only one of [--verbose, --dry-run, --help] can be set.");
        }
        $this->flags[$flag] = true;
    }

    private function cleanUpAndThrow(string $msg): never
    {
        $this->reset();
        throw new Exception($msg);
    }
}
