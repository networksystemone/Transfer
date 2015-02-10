<?php namespace Keios\TransferToTools\API\Contracts;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;

interface ApiCommandInterface
{
    public function __construct(TransferToApiKeyInterface $apiKey, array $arguments);

    public function getMethod();

    public function getBody();
} 