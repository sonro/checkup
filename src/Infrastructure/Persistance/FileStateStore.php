<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Sonro\Checkup\Domain\Model\State;
use Sonro\Checkup\Domain\Model\StateStoreInterface;

class FileStateStore implements StateStoreInterface
{
    public function __construct(
        private string $path,
        private Serializer $serializer,
    ) {
    }

    public function load(): State
    {
        // get data from file
        try {
            $content = file_get_contents($this->path);
            if ($content === false) {
                throw StoreError::fileRead($this->path);
            }
        } catch (\Throwable $_e) {
            throw StoreError::fileRead($this->path);
        }

        // deserialize data
        /** @var State|null */
        $state =  $this->serializer->deserialize($content, State::class);
        if (!$state instanceof State) {
            throw StoreError::fileParse($this->path);
        }

        return $state;
    }

    public function store(State $state): void
    {
        // serialize data
        $state =  $this->serializer->serialize($state);

        // write data to file
        $result = file_put_contents($this->path, $state);
        if ($result === false || $result === 0) {
            throw StoreError::fileWrite($this->path);
        }
    }
}
