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
     * The CDC server we send requests to.
     *
     * @var \SimpleSAML\Module\cdc\Server
     */
    private Server $server;


    /**
     * Initialize a CDC client.
     *
     * @param string $domain  The domain we should query the server for.
     */
    public function __construct(
        protected string $domain,
    ) {
        $this->server = new Server($domain);
    }


    /**
     * Receive a CDC response.
     *
     * @return array<mixed>|null  The response, or NULL if no response is received.
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
     * @param array<mixed> $params  Additional parameters.
     */
    public function sendRequest(string $returnTo, string $op, array $params = []): void
    {
        $params['op'] = $op;
        $params['return'] = $returnTo;
        $this->server->sendRequest($params);
    }
}
