<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

class Application
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        if (!$this->testInternetAccess()) {
            return;
        }

        $urlFile = $this->container->getConfig()->getTestSitesFile();
        $urlList = UrlListBuilder::buildFromFile($urlFile);

        $badUrls = $this->container->getUrlTester()->testList($urlList);

        if ($this->isBadUrlNew($badUrls)) {
            if (!empty($badUrls)) {
                $this->sendEmails($badUrls);
            }

            $this->saveBadUrls($badUrls);
        }
    }

    private function sendEmails(array $badUrls)
    {
        $urlList = implode("\n", $badUrls);
        $body = sprintf("Unable to reach sites:\n%s\n", $urlList);
        $subject = 'Sites are down';
        $this->container->getEmailer()->send(
            $subject, 
            $body, 
            $this->container->getConfig()->getEmailRecipients(),
            $this->container->getConfig()->getSmtpCredentials()
        );
    }

    private function saveBadUrls(array $badUrls)
    {
        $urlFile = $this->container->getConfig()->getBadSitesFile();
        $output = implode("\n", $badUrls);
        file_put_contents($urlFile, $output);
    }

    private function isBadUrlNew(array $badUrls): bool
    {
        $urlFile = $this->container->getConfig()->getBadSitesFile();
        if (!file_exists($urlFile)) {
            return true;
        }
        $oldUrls = UrlListBuilder::buildFromFile($urlFile);
        if (empty($oldUrls) && !empty($badUrls)) {
            return true;
        }
        foreach ($oldUrls as $oldUrl) {
            if (array_search($oldUrl, $badUrls, true) === false) {
                return true;
            }
        }

        return false;
    }

    private function testInternetAccess(): bool
    {
        $testUrl = $this->container->getConfig()->getTestUrl();
        $result = $this->container->getUrlTester()->testOne($testUrl, false);
        if (!$result) {
            $this->container->getLogger()
                ->error('Unreachable test URL', [$testUrl]);
        }

        return $result;
    }
}
