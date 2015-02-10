<?php namespace Keios\TransferToTools\API\Contracts;

interface ApiCommandInterface
{
    public function __construct(TransferToApiKeyInterface $apiKey, array $arguments);

    public function getMethod();

    public function getBody();
} 