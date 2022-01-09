<?php

namespace Sonro\Checkup\Domain\Model;

class SmtpCredentials
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $server,
        public readonly int $port,
        public readonly string $secureType
    ) {
    }
}
