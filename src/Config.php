<?php

namespace Sonro\Checkup;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Annotation\Type;

class Config
{
    /**
     * @var string
     * @Type("string")
     */
    private $testUrl;

    /**
     * @var float
     * @Type("float")
     */
    private $timeout;

    /**
     * @var EmailRecipient[]
     * @Type("array<Sonro\Checkup\EmailRecipient>")
     */
    private $emailRecipients;

    /**
     * @var SmtpCredentials
     * @Type("Sonro\Checkup\SmtpCredentials")
     */
    private $smtpCredentials;

    /**
     * Load conifg from jsonfile.
     *
     * @param string              $configFilename
     * @param SerializerInterface $serializer
     *
     * @return self
     */
    public static function loadFromFile(
        string $configFilename,
        SerializerInterface $serializer
    ) {
        $configString = file_get_contents($configFilename);
        if ($configString === false) {
            throw new \Exception("Unable to read config file: $configFilename");
        }
        $config = $serializer->deserialize($configString, self::class, 'json');

        return $config;
    }

    /**
     * Get the value of testUrl.
     *
     * @return string
     */
    public function getTestUrl()
    {
        return $this->testUrl;
    }

    /**
     * Get the value of emailRecipients.
     *
     * @return EmailRecipient[]
     */
    public function getEmailRecipients()
    {
        return $this->emailRecipients;
    }

    /**
     * Get the value of smtpCredentials.
     *
     * @return SmtpCredentials
     */
    public function getSmtpCredentials()
    {
        return $this->smtpCredentials;
    }

    /**
     * Get the value of timeout.
     *
     * @return float
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
