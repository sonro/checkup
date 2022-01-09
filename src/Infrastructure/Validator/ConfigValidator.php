<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Validator;

use Sonro\Checkup\Domain\Model\Config;

class ConfigValidator
{
    private EmailRecipientValidator $emailRecipientValidator;
    private SmtpCredentialsValidator $smtpCredentialsValidator;

    public function __construct()
    {
        $this->emailRecipientValidator = new EmailRecipientValidator();
        $this->smtpCredentialsValidator = new SmtpCredentialsValidator();
    }

    /**
     * @param Config $config
     * @return string[]
     */
    public function validate(Config $config): array
    {
        $errors = [];
        if (empty($config->testUrl)) {
            $errors[] = 'test_url is required';
        }

        if (empty($config->timeout)) {
            $errors[] = 'timout is required';
        }

        if (empty($config->emailRecipients)) {
            $errors[] = 'email_recipients is required';
        }
        foreach ($config->emailRecipients as $emailRecipient) {
            $emailErrors = $this->emailRecipientValidator->validate($emailRecipient);
            if (!empty($emailErrors)) {
                $errors = array_merge($errors, $emailErrors);
            }
        }

        $smtpErrors = $this->smtpCredentialsValidator->validate($config->smtpCredentials);
        if (!empty($smtpErrors)) {
            $errors = array_merge($errors, $smtpErrors);
        }

        if (empty($config->urls)) {
            $errors[] = 'urls is required';
        }

        return $errors;
    }
}