<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain\Model;

class EmailRecipient
{
    public function __construct(
        public readonly string $name,
        public readonly string $email
    ) {
    }
}