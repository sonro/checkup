<?php

declare(strict_types=1);

namespace Sonro\Checkup\Infrastructure\Validator;

use Sonro\Checkup\Domain\Model\EmailRecipient;

class EmailRecipientValidator
{
    /**
     * @param EmailRecipient $emailRecipient
     * @return string[]
     */
    public function validate(EmailRecipient $emailRecipient): array
    {
        $errors = [];
        if (empty($emailRecipient->name)) {
            $errors[] = 'name is required';
        }

        if (empty($emailRecipient->email)) {
            $errors[] = 'email is required';
        }

        return $errors;
    }
}