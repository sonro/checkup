<?php

namespace App;

use GuzzleHttp\Psr7\Request;

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
        $this->testInternetAcess();
    }

    private function testInternetAcess()
    {
        $testRequest = new Request(
            'GET',
            $this->container->getConfig()->getTestUrl()
        );
        $testResponse = $this->container->getClient()->send($testRequest);
        var_dump($testResponse);
    }
}
