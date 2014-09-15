<?php

namespace mbfisher\Web\Test\AcceptanceTest;

trait AcceptanceTestTrait
{
    private $client;

    public function setClient(ApplicationClientInterface $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return HttpKernelInterface
     */
    abstract public function buildApplication();

    public function initialize()
    {
        $app = $this->buildApplication();
        $client = new ApplicationClient($app);
        $this->setClient($client);
    }

    public function get($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->getClient()->request($uri, 'GET', $parameters, $cookies, $files, $server, $content);
    }

    public function post($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->getClient()->request($uri, 'POST', $parameters, $cookies, $files, $server, $content);
    }
}
