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
        $this->getClient()->pushRequest($request);
    }

    /**
     * @When I request :signature with payload
     */
    public function iRequestWithPayload($signature, PyStringNode $string)
    {
        list($method, $path) = explode(' ', $signature);
        $request = Request::create($path, $method, [], [], [], [], $string->getRaw());
        $request->headers->set('Content-type', 'application/json');
        $this->getClient()->pushRequest($request);
    }

    /**
     * @Then I get a :status response
     */
    public function iGetAResponse($status)
    {
        $response = $this->getClient()->getResponse();
        assertSame((int) $status, $response->getStatusCode(), $response);
    }
}
