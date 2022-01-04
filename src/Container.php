<?php

namespace Sonro\Checkup;

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

    /**
     * @var Emailer
     */
    private $emailer;

    public function __construct(
        SerializerInterface $serializer,
        Config $config,
        LoggerInterface $logger,
        UrlTester $urlTester,
        Emailer $emailer
    ) {
        $this->serializer = $serializer;
        $this->config = $config;
        $this->logger = $logger;
        $this->urlTester = $urlTester;
        $this->emailer = $emailer;
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

    /**
     * Get the value of emailer.
     *
     * @return Emailer
     */
    public function getEmailer()
    {
        return $this->emailer;
    }
}
