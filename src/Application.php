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

        var_dump($badUrls);
    }

    private function testInternetAccess(): bool
    {
        $testUrl = $this->container->getConfig()->getTestUrl();
        $result = $this->container->getUrlTester()->testOne($testUrl, false);
        if (!$result) {
            $this->container->getLogger()
                ->warning('Unreachable test URL', [$testUrl]);
        }

        return $result;
    }
}
