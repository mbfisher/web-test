<?php

namespace mbfisher\Web\Test\AcceptanceTest;

interface ApplicationClientInterface
{
    public function request($uri, $method = 'GET', $parameters = [], $cookies = [], $files = [], $server = [], $content = null);
}
