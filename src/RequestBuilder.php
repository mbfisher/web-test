<?php

use Symfony\Component\HttpFoundation\Request;

class RequestBuilder
{
    private $uri;
    private $method;
    private $parameters = [];
    private $cookies = [];
    private $files = [];
    private $server = [];
    private $content;

    public function __construct($method = null, $uri = null)
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    public function getRequest()
    {
        return Request::create(
            $this->uri,
            $this->method,
            $this->parameters,
            $this->cookies,
            $this->files,
            $this->server,
            $this->content
        );
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
