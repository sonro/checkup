<?php

namespace App;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

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
     * @var UrlTester
     */
    private $urlTester;

    public function __construct(
        SerializerInterface $serializer,
        Config $config,
        LoggerInterface $logger,
        UrlTester $urlTester
    ) {
        $this->serializer = $serializer;
        $this->config = $config;
        $this->logger = $logger;
        $this->urlTester = $urlTester;
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
     * Get the value of urlTester.
     *
     * @return UrlTester
     */
    public function getUrlTester()
    {
        return $this->urlTester;
    }
}
