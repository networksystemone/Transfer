<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientContactsGroupDeleteApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.contacts.group.delete';

    protected $method = 'POST';

    protected $requiredArguments = ['groupId'];

    public function getBody()
    {
        $requestBody['groupId'] = $this->arguments['groupId'];
        return json_encode($requestBody);
    }
}
