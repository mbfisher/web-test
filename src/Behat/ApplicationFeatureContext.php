<?php

namespace mbfisher\Web\Test\Behat;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;

use Symfony\Component\HttpFoundation\Request;

/**
 * Behat context class.
 */
abstract class ApplicationFeatureContext implements SnippetAcceptingContext
{
    abstract public function buildApplication();

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $app = $this->buildApplication();
        $client = new ApplicationClient($app);
        $this->setClient($client);
    }

    public function setClient(ApplicationClient $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @When I request :signature
     */
    public function iRequest($signature)
    {
        list($method, $path) = explode(' ', $signature);
        $request = Request::create($path, $method);
        $this->client->flush();
        $this->getClient()->pushRequest($request);
    }

    /**
     * @When I request :signature with payload
     */
    public function iRequestWithJsonPayload($signature, PyStringNode $string)
    {
        list($method, $path) = explode(' ', $signature);
        $request = Request::create($path, $method, [], [], [], [], $string->getRaw());
        $request->headers->set('Content-Type', 'application/json');
        $this->getClient()->pushRequest($request);
    }

    /**
     * @When I request :arg1 with form payload
     */
    public function iRequestWithFormPayload($signature, PyStringNode $string)
    {
        list($method, $path) = explode(' ', $signature);
        $request = Request::create($path, $method, json_decode($string->getRaw(), true));
        $request->headers->set('Content-Type', 'application/x-www-form-urlencoded');
        $this->getClient()->pushRequest($request);
    }

    /**
     * @Then I get redirected to :location
     */
    public function iGetRedirectedTo($location)
    {
        $response = $this->getClient()->getResponse();
        \assertTrue($response->isRedirection(), $response);
        \assertEquals($location, $response->headers->get('Location'));
    }

    /**
     * @Then I get a :status response
     */
    public function iGetAResponse($status)
    {
        $response = $this->getClient()->getResponse();
        \assertSame((int) $status, $response->getStatusCode(), $response);
    }

    /**
     * @Then it contains :type
     */
    public function itContains($type)
    {
        $response = $this->getClient()->getResponse();
        $contentType = $response->headers->get('Content-type');

        switch ($type) {
        case 'JSON':
            \assertContains('application/json', $contentType);
            break;
        }
    }

    /**
     * @Then it has a payload
     */
    public function itHasAPayload(PyStringNode $string)
    {
        $response = $this->getClient()->getResponse();
        \assertSame($string->getRaw(), $response->getContent());
    }

    /**
     * @Then the headers contain
     */
    public function theHeadersContain(PyStringNode $string)
    {
        $response = $this->getClient()->getResponse();

        foreach ($string->getStrings() as $header) {
            list($name, $value) = preg_split('/\s*:\s*/', $header, 2);
            \assertTrue($response->headers->has($name));
            \assertEquals($value, $response->headers->get($name));
        }
    }
}
