<?php namespace Keios\TransferToTools\API\Contracts;

use Keios\TransferToTools\API\TransferToApiKey;

interface RequestClientInterface
{
    /**
     * Prepare HTTP Client engine
     * @param TransferToApiKeyInterface $apiKey
     * @return mixed
     */
    public function boot(TransferToApiKeyInterface $apiKey);

    public function processRequest($method, $url, $body);

    public function getLastStatusCode();
} 