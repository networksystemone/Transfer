<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientContactsGroupGetApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.contacts.group.get';

    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        return '{}';
    }
}
