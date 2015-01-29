<?php namespace Keios\TransferToTools\API\ApiCommands;


use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ApiTestApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $apiSubUrl = 'json/syncreply/api.test';

    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        return '{}';
    }
} 