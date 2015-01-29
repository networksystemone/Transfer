<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientContactsDeleteApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.contacts.delete';

    protected $method = 'POST';

    protected $requiredArguments = ['id'];

    public function getBody()
    {
        $requestBody['id'] = $this->arguments['id'];
        return json_encode($requestBody);
    }
}
