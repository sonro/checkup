<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Infrastructure\Persistance;

use PHPUnit\Framework\TestCase;
use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Infrastructure\Persistance\FileConfigStore;
use Sonro\Checkup\Infrastructure\Persistance\Serializer;

class FileConfigStoreTest extends TestCase
{
    const DIR_ENV_NAME = 'CHECKUP_APP_DIR';

    public function test_load_config_success(): void
    {
        $store = $this->createFileConfigStore();
        $config = $store->load();
        $this->assertInstanceOf(Config::class, $config);
    } 

    private function createFileConfigStore(): FileConfigStore
    {
        $serializer = $this->createSerailizer();
        return new FileConfigStore(
            $this->testFilePath(),
            $serializer,
        );
    }

    private function testFilePath(): string
    {
        return getenv(self::DIR_ENV_NAME).'/config.json';
    }

    private function createSerailizer(): Serializer
    {
        return new Serializer();
    }
}