<?php

namespace App;

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

        $urlList = UrlListBuilder::buildFromFile(APP_TESTSITES_FILE_NAME);

        $badUrls = $this->container->getUrlTester()->testList($urlList);

        if ($this->isBadUrlNew($badUrls)) {
            if (!empty($badUrls)) {
                $this->sendBadUrls($badUrls);
                $this->container->getLogger()->info('Alerting sysadmins');
            } else {
                $this->sendOk();
                $this->container->getLogger()->info('All sites now working');
            }

            $this->saveBadUrls($badUrls);
        }
    }

    private function sendOk()
    {
        $body = 'All test sites online!';
        $subject = 'Sites are online';
        $this->container->getEmailer()->send(
            $subject,
            $body,
            $this->container->getConfig()->getEmailRecipients(),
            $this->container->getConfig()->getSmtpCredentials()
        );
    }

    private function sendBadUrls(array $badUrls)
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
        $output = implode("\n", $badUrls);
        file_put_contents(APP_BADSITES_FILE_NAME, $output);
    }

    private function isBadUrlNew(array $badUrls): bool
    {
        if (!file_exists(APP_BADSITES_FILE_NAME)) {
            return true;
        }
        $oldUrls = UrlListBuilder::buildFromFile(APP_BADSITES_FILE_NAME);
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
