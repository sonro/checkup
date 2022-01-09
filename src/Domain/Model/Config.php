<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

use JMS\Serializer\Annotation\Type;

class Config
{
    /**
     * @var EmailRecipient[]
     * @Type("array<Sonro\Checkup\Domain\Model\EmailRecipient>")
     */
    public readonly array $emailRecipients;

    /**
     * @var SmtpCredentials
     * @Type("Sonro\Checkup\Domain\Model\SmtpCredentials")
     */
    public readonly SmtpCredentials $smtpCredentials;

    /**
     * @var string[]
     * @Type("array<string>")
     */
    public readonly array $urls;

    /**
     * @param string $testUrl
     * @param float $timeout
     * @param EmailRecipient[] $emailRecipients
     * @param SmtpCredentials $smtpCredentials
     * @param string[] $urls
     */
    public function __construct(
        public readonly string $testUrl,
        public readonly float $timeout,
        array $emailRecipients,
        SmtpCredentials $smtpCredentials,
        array $urls,
    ) {
        $this->emailRecipients = $emailRecipients;
        $this->smtpCredentials = $smtpCredentials;
        $this->urls = $urls;
    }
}
