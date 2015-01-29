<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class AdminLogonApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/AdminLogOn';

    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        return '{}';
    }
}
