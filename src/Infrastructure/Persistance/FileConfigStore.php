<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Domain\Model\ConfigStoreInterface;
use Sonro\Checkup\Infrastructure\Validator\ConfigValidator;

class FileConfigStore implements ConfigStoreInterface
{
    private ConfigValidator $configValidator;

    public function __construct(
        private string $path,
        private Serializer $serializer,
    ) {
        $this->configValidator = new ConfigValidator();
    }

    public function load(): Config
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
        /** @var Config|null */
        $config =  $this->serializer->deserialize($content, Config::class);
        if (!$config instanceof Config) {
            throw StoreError::fileParse($this->path);
        }

        // validate data
        $errors = $this->configValidator->validate($config);
        if (!empty($errors)) {
            throw StoreError::validateError($errors);
        }

        return $config;
    }
}
