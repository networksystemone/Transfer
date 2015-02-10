<?php namespace Keios\TransferToTools\API\Contracts;

use Keios\TransferToTools\API\TransferToApiKey;

interface RequestClientInterface
{
    /**
     * Prepare HTTP Client engine
     * @param TransferToApiKeyInterface $apiKey
     * @return mixed
     */
    public function boot();

    public function processRequest($method, $body);

    public function getLastStatusCode();
} 