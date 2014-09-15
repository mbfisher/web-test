<?php

namespace mbfisher\Web\Test\AcceptanceTest;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class ApplicationClient implements ApplicationClientInterface
{
    private $application;

    public function __construct(HttpKernelInterface $application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function request($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $request = $this->createRequest($uri, $method, $parameters, $cookies, $files, $server, $content);
        return $this->getApplication()->handle($request);
    }

    public function createRequest($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
    }
}
