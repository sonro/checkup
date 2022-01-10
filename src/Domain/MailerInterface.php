<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain;

use Sonro\Checkup\Domain\Model\SmtpCredentials;
use Sonro\Checkup\Domain\Model\EmailRecipient;

interface MailerInterface
{
    /**
     * @param string $subject
     * @param string $body
     * @param EmailRecipient[] $recipients
     * @param SmtpCredentials $credentials
     * @return bool
     */
    public function send(
        string $subject,
        string $body,
        array $recipients,
        SmtpCredentials $credentials,
    ): bool;
}
