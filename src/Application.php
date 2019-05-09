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

        $urlFile = $this->container->getConfig()->getTestSitesFile();
        $urlList = UrlListBuilder::buildFromFile($urlFile);

        $badUrls = $this->container->getUrlTester()->testList($urlList);
        if (empty($badUrls)) {
            return;
        }

        if ($this->isBadUrlNew($badUrls)) {
            $this->saveBadUrls($badUrls);
            $this->sendEmails($badUrls);
        }

        var_dump($badUrls);
    }

    private function sendEmails(array $badUrls)
    {
        echo "Sending emails\n";
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
        foreach ($badUrls as $newUrl) {
            if (array_search($newUrl, $oldUrls, true) === false) {
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
