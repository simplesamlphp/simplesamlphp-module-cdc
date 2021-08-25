<?php

declare(strict_types=1);

namespace SimpleSAML\Module\cdc\Controller;

use SimpleSAML\Auth;
use SimpleSAML\Configuration;
use SimpleSAML\Error;
use SimpleSAML\HTTP\RunnableResponse;
use SimpleSAML\Module\cdc\Client;
use SimpleSAML\Module\cdc\Server;
use SimpleSAML\Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller class for the cdc module.
 *
 * This class serves the different views available in the module.
 *
 * @package simplesamlphp/simplesamlphp-module-cdc
 */
class CDC
{
    /** @var \SimpleSAML\Configuration */
    protected Configuration $config;

    /** @var \SimpleSAML\Session */
    protected Session $session;


    /**
     * Controller constructor.
     *
     * It initializes the global configuration and session for the controllers implemented here.
     *
     * @param \SimpleSAML\Configuration $config The configuration to use by the controllers.
     * @param \SimpleSAML\Session $session The session to use by the controllers.
     *
     * @throws \Exception
     */
    public function __construct(
        Configuration $config,
        Session $session
    ) {
        $this->config = $config;
        $this->session = $session;
    }


    /**
     * Server
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The current request.
     *
     * @return \SimpleSAML\HTTP\RunnableResponse
     */
    public function server(Request $request): RunnableResponse
    {
        return new RunnableResponse([Server::class, 'processRequest'], []);
    }


    /**
     * Resume
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The current request.
     *
     * @return \SimpleSAML\HTTP\RunnableResponse
     */
    public function resume(Request $request): RunnableResponse
    {
        $domain = $request->get('domain');
        if ($domain === null) {
            throw new Error\BadRequest('Missing domain to CDC resume handler.');
        }

        $client = new Client($domain);

        $response = $client->getResponse();
        if ($response === null) {
            throw new Error\BadRequest('Missing CDC response to CDC resume handler.');
        }

        if (!isset($response['id'])) {
            throw new Error\BadRequest('CDCResponse without id.');
        }

        $state = Auth\State::loadState($response['id'], 'cdc:resume');
        if (is_null($state)) {
            throw new Error\NoState();
        }

        return new RunningResponse([Auth\ProcessingChain::class, 'resumeProcessing'], [$state]);
    }
}
