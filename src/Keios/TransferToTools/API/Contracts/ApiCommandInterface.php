<?php namespace Keios\TransferToTools\API\Contracts;

interface ApiCommandInterface
{
    public function __construct(array $arguments);

    public function getApiSubUrl();

    public function getMethod();

    public function getBody();
} 