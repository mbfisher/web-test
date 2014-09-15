<?php

namespace mbfisher\Web\Test\AcceptanceTest;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

trait AcceptanceTestTrait
{
    private $application;

    /**
     * @return HttpKernelInterface
     */
    abstract public function buildApplication();

    public function handle(Request $request)
    {
        $app = $this->buildApplication();
        return $app->handle($request);
    }

    public function get($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->createRequest($uri, 'GET', $parameters, $cookies, $files, $server, $content);
    }

    public function post($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->createRequest($uri, 'POST', $parameters, $cookies, $files, $server, $content);
    }

    public function createRequest($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
    }
}
