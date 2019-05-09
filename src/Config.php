<?php

namespace App;

use JMS\Serializer\SerializerInterface;

class Config
{
    /**
     * @var string
     */
    private $testUrl;

    /**
     * @var string
     */
    private $testSitesFile;

    /**
     * @var string
     */
    private $badSitesFile;

    /**
     * @var EmailRecipient[]
     */
    private $emailRecipients;

    /**
     * @var SmtpCredentials
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
     * Get the value of testSitesFile.
     *
     * @return string
     */
    public function getTestSitesFile()
    {
        return $this->testSitesFile;
    }

    /**
     * Get the value of badSitesFile.
     *
     * @return string
     */
    public function getBadSitesFile()
    {
        return $this->badSitesFile;
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
}
