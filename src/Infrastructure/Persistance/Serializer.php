<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Persistance;

use Exception;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Serializer as JmsSerializer;

class Serializer
{
    private JmsSerializer $serializer;

    public function __construct() 
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function serialize(object $object): string
    {
        return $this->serializer->serialize($object, 'json');
    }

    public function deserialize(string $data, string $class): Object
    {
        try {
            /** @var ?Object $object */
            $object = $this->serializer->deserialize($data, $class, 'json');
        } catch (Exception $e) {
            throw StoreError::invalidJson();
        }

        if (!$object || !$object instanceof $class) {
            throw StoreError::desirializeError($class);
        }

        return $object;
    }
}