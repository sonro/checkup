<?php

declare(strict_types=1);

namespace Sonro\Checkup\Tests\Unit\Domain;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sonro\Checkup\Domain\CheckupError;
use Sonro\Checkup\Domain\CheckupService;
use Sonro\Checkup\Domain\MailerInterface;
use Sonro\Checkup\Domain\Model\Config;
use Sonro\Checkup\Domain\Model\EmailRecipient;
use Sonro\Checkup\Domain\Model\SmtpCredentials;
use Sonro\Checkup\Domain\Model\State;
use Sonro\Checkup\Domain\UrlTesterInterface;

class CheckupServiceTest extends TestCase
{
    private const TEST_URL = 'http://google.com';

    public function test_execute_success(): void
    {
        $this->checkExecute();
        // no return only side effects
        $this->assertTrue(true);
    }

    public function test_execute_error_no_internet_connection(): void
    {
        $urlTester = $this->createMockUrlTester();
        $urlTester->method('testOne')->with(self::TEST_URL)->willReturn(false);

        $this->expectException(CheckupError::class);
        $this->expectExceptionCode(CheckupError::CODE_NO_INTERNET_CONNECTION);

        $this->checkExecute(urlTester: $urlTester);
    }

    private function checkExecute(
        Config $config = null,
        State $state = null,
        UrlTesterInterface $urlTester = null,
        MailerInterface $mailer = null,
    ): void
    {
        $service = $this->createCheckupService(
            urlTester: $urlTester,
            mailer: $mailer,
        );
        $config = $config ?? $this->testConfig();
        $state = $state ?? $this->testState();
        $service->execute($config, $state);
    }

    private function createCheckupService(
        MailerInterface $mailer = null,
        UrlTesterInterface $urlTester = null,
    ): CheckupService {
        $logger = $this->createStub(LoggerInterface::class);
        $mailer = $mailer ?? $this->mockMailerSuccess();
        $urlTester = $urlTester ?? $this->mockUrlTesterSuccess();
        
        return new CheckupService($logger, $mailer, $urlTester);
    }

    private function testConfig(): Config
    {
        return new Config(
            testUrl: self::TEST_URL,
            timeout: 10.0,
            emailRecipients: $this->testEmailRecipients(),
            smtpCredentials: $this->testSmtpCredentials(),
            urls: $this->testUrls(),
        );
    }

    private function testState(): State
    {
        return new State();
    }

    private function mockMailerSuccess(): MailerInterface
    {
        $mailer = $this->createMockMailer();

        $mailer->method('send')->willReturn(true);

        return $mailer;
    }


    private function mockUrlTesterSuccess(): UrlTesterInterface
    {
        $mock = $this->createMockUrlTester();

        $mock->method('testOne')->willReturn(true);

        $mock->method('testList')->willReturn([]);

        return $mock;
    }

    private function testEmailRecipients(): array
    {
        return [
            new EmailRecipient('Steve', 'steve@example.com'),
            new EmailRecipient('James', 'james@example.com'),
        ];
    }

    private function testSmtpCredentials(): SmtpCredentials
    {
        return new SmtpCredentials(
            username: "test-username",
            password: "test-password",
            server: "test.server.com",
            port: 465,
            secureType: 'ssl',
        );
    }

    private function testUrls(): array
    {
        return [
            'http://example.com',
            'http://example.com/foo',
            'http://example.com/bar',
        ];
    }

    /**
     * @return UrlTesterInterface|MockObject
     */
    private function createMockUrlTester(): UrlTesterInterface
    {
        return $this->createMock(UrlTesterInterface::class);

    }

    /**
     * @return MailerInterface|MockObject
     */
    private function createMockMailer(): MailerInterface
    {
        return $this->createMock(MailerInterface::class);
    }
}
