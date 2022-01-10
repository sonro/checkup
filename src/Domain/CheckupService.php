<?php

declare(strict_types=1);

namespace Sonro\Checkup\Domain;

use Psr\Log\LoggerInterface;
use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Domain\Model\State;

class CheckupService
{
    public function __construct(
        private LoggerInterface $logger,
        private MailerInterface $mailer,
        private UrlTesterInterface $urlTester,
    )
    {
    }

    public function execute(Config $config, State $state): void
    {
        $this->testInternetAccess($config->testUrl);
    }

    private function testInternetAccess(string $url): void
    {
        $this->logger->debug('Testing internet access');
        if (!$this->urlTester->testOne($url)) {
            throw CheckupError::noInternetConnection($url);
        }
        $this->logger->debug('Internet access OK');
    }
}
