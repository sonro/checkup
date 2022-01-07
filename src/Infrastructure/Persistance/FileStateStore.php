<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Sonro\Checkup\Domain\Model\State;
use Sonro\Checkup\Domain\Model\StateStoreInterface;

class FileStateStore implements StateStoreInterface
{
    public function __construct(
        private string $path,
    ) {
    }

    public function load(): State
    {
        return new State();
    }

    public function store(State $state): void
    {
    }
}
