<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Validator;

use Sonro\Checkup\Domain\Model\SmtpCredentials;

class SmtpCredentialsValidator
{
    /**
     * @param SmtpCredentials $smtpCredentials
     * @return string[]
     */
    public function validate(SmtpCredentials $smtpCredentials): array
    {
        $errors = [];
        if (empty($smtpCredentials->username)) {
            $errors[] = 'username is required';
        }

        if (empty($smtpCredentials->password)) {
            $errors[] = 'password is required';
        }

        if (empty($smtpCredentials->server)) {
            $errors[] = 'server is required';
        }

        if (empty($smtpCredentials->port)) {
            $errors[] = 'port is required';
        }

        if (empty($smtpCredentials->secureType)) {
            $errors[] = 'secureType is required';
        }

        return $errors;
    }
}