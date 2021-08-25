<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Module\cdc\Controller;

use PHPUnit\Framework\TestCase;
//use SimpleSAML\Auth;
use SimpleSAML\Configuration;
use SimpleSAML\Error;
use SimpleSAML\HTTP\RunnableResponse;
use SimpleSAML\Module\cdc\Controller;
use SimpleSAML\Session;
//use SimpleSAML\Utils;
//use SimpleSAML\XHTML\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Set of tests for the controllers in the "cdc" module.
 *
 * @covers \SimpleSAML\Module\cdc\Controller\CDC
 */
class CDCTest extends TestCase
{
    /** @var \SimpleSAML\Configuration */
    protected Configuration $config;

    /** @var \SimpleSAML\Session */
    protected Session $session;


    /**
     * Set up for each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config = Configuration::loadFromArray(
            [
                'module.enable' => ['cdc' => true],
            ],
            '[ARRAY]',
            'simplesaml'
        );

        Configuration::setPreLoadedConfig(
            Configuration::loadFromArray(
                [
                    'example.org' => [
                        'key' => 'SomethingCompleteDifferent',
                        'server' => 'https://my-cdc.example.org/simplesaml/module.php/cdc/server.php',
                        'cookie.lifetime' => 0,
                    ],
                ],
                '[ARRAY]',
                'simplesaml'
            ),
            'module_cdc.php',
            'simplesaml'
        );

        $this->session = Session::getSessionFromRequest();

        Configuration::setPreLoadedConfig($this->config, 'config.php');
    }


    /**
     * Test that accessing the resume-endpoint with default configured key results in an error-response
     *
     * @return void
     */
    public function testResumeDomainDefaultKey(): void
    {
        Configuration::setPreLoadedConfig(
            Configuration::loadFromArray(
                [
                    'example.org' => [
                        'key' => 'ExampleSharedKey',
                        'server' => 'https://my-cdc.example.org/simplesaml/module.php/cdc/server.php',
                        'cookie.lifetime' => 0,
                    ],
                ],
                '[ARRAY]',
                'simplesaml'
            ),
            'module_cdc.php',
            'simplesaml'
        );


        $request = Request::create(
            '/resume',
            'GET',
            ['domain' => 'example.org']
        );

        $c = new Controller\CDC($this->config, $this->session);

        $this->expectException(Error\Exception::class);
        $this->expectExceptionMessage("Key for CDC domain 'example.org' not changed from default.");

        $c->resume($request);
    }


    /**
     * Test that accessing the resume-endpoint without domain results in an error-response
     *
     * @return void
     */
    public function testResumeNoDomain(): void
    {
        $request = Request::create(
            '/resume',
            'GET',
        );

        $c = new Controller\CDC($this->config, $this->session);

        $this->expectException(Error\BadRequest::class);
        $this->expectExceptionMessage("BADREQUEST('%REASON%' => 'Missing domain to CDC resume handler.')");

        $c->resume($request);
    }


    /**
     * Test that accessing the resume-endpoint with unknown domain results in an error-response
     *
     * @return void
     */
    public function testResumeUnknownDomain(): void
    {
        $request = Request::create(
            '/resume',
            'GET',
            ['domain' => 'non-existing.org'],
        );

        $c = new Controller\CDC($this->config, $this->session);

        $this->expectException(Error\Exception::class);
        $this->expectExceptionMessage("Unknown CDC domain: 'non-existing.org'");

        $c->resume($request);
    }


    /**
     * Test that accessing the resume-endpoint with domain results in a RunnableResponse
     *
     * @return void
     */
    public function testResumeDomain(): void
    {
        $request = Request::create(
            '/resume',
            'GET',
            ['domain' => 'example.org'],
        );

        $c = new Controller\CDC($this->config, $this->session);

// @TODO: Inject Server & Client objects and test entire workflow
        $this->expectException(Error\BadRequest::class);
        $this->expectExceptionMessage("BADREQUEST('%REASON%' => 'Missing CDC response to CDC resume handler.')");
//        $this->assertTrue($response->isSuccessful());
//        $this->assertInstanceOf(RunnableResponse::class, $response);

        $response = $c->resume($request);
    }


    /**
     * Test that accessing the server-endpoint results in a RunnableResponse
     *
     * @return void
     */
    public function testServer(): void
    {
        $request = Request::create(
            '/server',
            'GET',
        );

        $c = new Controller\CDC($this->config, $this->session);

        $response = $c->server($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf(RunnableResponse::class, $response);
    }
}
