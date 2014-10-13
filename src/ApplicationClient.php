<?php

namespace mbfisher\Web\Test\Behat;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class ApplicationClient
{
    private $application;
    private $queue = [];

    public function __construct(HttpKernelInterface $application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function reset()
    {
        $this->queue = [];
    }

    public function pushRequest(Request $request)
    {
        $this->queue[] = $request;
    }

    public function getRequest()
    {
        return array_shift($this->queue);
    }

    public function getResponse()
    {
        if (!$request = $this->getRequest()) {
            throw new \UnexpectedValueException("Request queue empty");
        }

        return $this->getApplication()->handle($request);
    }
}
