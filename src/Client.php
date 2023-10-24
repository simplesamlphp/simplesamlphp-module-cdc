<?php

declare(strict_types=1);

namespace SimpleSAML\Module\cdc;

/**
 * CDC client class.
 *
 * @package SimpleSAMLphp
 */

class Client
{
    /**
     * Our CDC domain.
     *
     * @var string
     */
    private string $domain;

    /**
     * The CDC server we send requests to.
     *
     * @var Server
     */
    private Server $server;


    /**
     * Initialize a CDC client.
     *
     * @param string $domain  The domain we should query the server for.
     */
    public function __construct(string $domain)
    {
        $this->domain = $domain;
        $this->server = new Server($domain);
    }


    /**
     * Receive a CDC response.
     *
     * @return array|null  The response, or NULL if no response is received.
     */
    public function getResponse(): ?array
    {
        return $this->server->getResponse();
    }


    /**
     * Send a request.
     *
     * @param string $returnTo  The URL we should return to afterwards.
     * @param string $op  The operation we are performing.
     * @param array $params  Additional parameters.
     */
    public function sendRequest(string $returnTo, string $op, array $params = []): void
    {
        $params['op'] = $op;
        $params['return'] = $returnTo;
        $this->server->sendRequest($params);
    }
}
