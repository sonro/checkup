<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Persistance;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Domain\Model\State;
use Sonro\Checkup\Infrastructure\Persistance\FileStateStore;
use Sonro\Checkup\Infrastructure\Persistance\Serializer;

class FileStateStoreTest extends TestCase
{
    const DIR_ENV_NAME = 'CHECKUP_APP_DIR';

    public function test_load_state_success(): void
    {
        $store = $this->createFileStateStore();
        $state = $store->load();
        $this->assertInstanceOf(State::class, $state);
    }

    private function createFileStateStore(): FileStateStore
    {
        $serializer = $this->createSerailizer();
        return new FileStateStore(
            $this->testFilePath(),
            $serializer,
        );
    }

    private function testFilePath(): string
    {
        return getenv(self::DIR_ENV_NAME).'/state.json';
    }

    private function createSerailizer(): Serializer
    {
        return new Serializer();
    }
}