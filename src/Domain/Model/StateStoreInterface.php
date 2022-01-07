<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

interface StateStoreInterface
{
    public function load(): State;

    public function store(State $state): void;
}
