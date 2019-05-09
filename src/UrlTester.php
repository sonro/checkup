<?php

namespace App;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use function GuzzleHttp\Promise\settle;

class UrlTester
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Test list of urls.
     *
     * @param array $urlList
     *
     * @return array list of bad urls
     */
    public function testList(array $urlList): array
    {
        $promises = [];
        foreach ($urlList as $url) {
            $promises[$url] = $this->client->requestAsync('GET', $url);
        }

        $results = settle($promises)->wait();
        $badUrls = [];
        foreach ($results as $url => $result) {
            if ($result['state'] === 'rejected') {
                $badUrls[] = $url;
                $this->logger->warning('Unable to reach URL', [$url]);
            }
        }

        return $badUrls;
    }

    /**
     * Test one url.
     *
     * @param string $url
     * @param bool   $log = true
     *
     * @return bool
     */
    public function testOne(string $url, bool $log = true): bool
    {
        try {
            $this->client->request('GET', $url);
        } catch (\Exception $e) {
            if ($log) {
                $this->logger->warning('Unable to reach URL', [$e->getMessage()]);
            }

            return false;
        }

        return true;
    }
}
