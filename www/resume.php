<?php

use SimpleSAML\Auth;
use SimpleSAML\Error;

if (!array_key_exists('domain', $_REQUEST)) {
    throw new Error\BadRequest('Missing domain to CDC resume handler.');
}

$domain = (string) $_REQUEST['domain'];
$client = new \SimpleSAML\Module\cdc\Client($domain);

$response = $client->getResponse();
if ($response === null) {
    throw new Error\BadRequest('Missing CDC response to CDC resume handler.');
}

if (!isset($response['id'])) {
    throw new Error\BadRequest('CDCResponse without id.');
}

$state = Auth\State::loadState($response['id'], 'cdc:resume');
if (!is_null($state)) {
    Auth\ProcessingChain::resumeProcessing($state);
} else {
    throw new Error\NoState();
}
