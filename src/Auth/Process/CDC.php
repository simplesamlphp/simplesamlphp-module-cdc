<?php

declare(strict_types=1);

namespace SimpleSAML\module\cdc\Auth\Process;

use SimpleSAML\Auth;
use SimpleSAML\Error;
use SimpleSAML\Logger;
use SimpleSAML\Module;
use SimpleSAML\Module\cdc\Client;

/**
 * Filter for setting the SAML 2 common domain cookie.
 *
 * @package SimpleSAMLphp
 */
class CDC extends Auth\ProcessingFilter
{
    /**
     * Our CDC domain.
     *
     * @var string
     */
    private string $domain;

    /**
     * Our CDC client.
     *
     * @var \SimpleSAML\Module\cdc\Client
     */
    private Client $client;


    /**
     * Initialize this filter.
     *
     * @param array $config  Configuration information about this filter.
     * @param mixed $reserved  For future use.
     */
    public function __construct(array $config, $reserved)
    {
        parent::__construct($config, $reserved);

        if (!isset($config['domain'])) {
            throw new Error\Exception('Missing domain option in cdc:CDC filter.');
        }
        $this->domain = (string) $config['domain'];

        $this->client = new \SimpleSAML\Module\cdc\Client($this->domain);
    }


    /**
     * Redirect to page setting CDC.
     *
     * @param array &$state  The request state.
     */
    public function process(array &$state): void
    {
        if (!isset($state['Source']['entityid'])) {
            Logger::warning('saml:CDC: Could not find IdP entityID.');
            return;
        }

        // Save state and build request
        $id = Auth\State::saveState($state, 'cdc:resume');

        $returnTo = Module::getModuleURL('cdc/resume.php', ['domain' => $this->domain]);

        $params = [
            'id' => $id,
            'entityID' => $state['Source']['entityid'],
        ];
        $this->client->sendRequest($returnTo, 'append', $params);
    }
}
