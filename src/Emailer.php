<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use Psr\Log\LoggerInterface;

class Emailer
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send(
        string $subject,
        string $body,
        array $recipients,
        SmtpCredentials $credentials
    ) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $credentials->getServer();
            $mail->SMTPAuth = true;
            $mail->Username = $credentials->getUsername();
            $mail->Password = $credentials->getPassword();
            $mail->SMTPSecure = $credentials->getSecureType();
            $mail->Port = $credentials->getPort();

            $mail->setFrom($credentials->getUsername());
            foreach ($recipients as $recipient) {
                $name = $recipient->getName();
                if ($name) {
                    $mail->addAddress($recipient->getEmail(), $name);
                } else {
                    $mail->addAddress($recipient->getEmail());
                }
            }

            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (\Exception $e) {
            $this->logger->error('Error sending email', [$e->getMessage()]);
        }
    }
}
