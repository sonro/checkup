<?php

namespace App;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp\ClientInterface;

class Container
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(
        SerializerInterface $serializer,
        Config $config,
        LoggerInterface $logger,
        ClientInterface $client
    ) {
        $this->serializer = $serializer;
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
    }

    /**
     * Get the value of serializer.
     *
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * Get the value of config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the value of logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Get the value of client.
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
