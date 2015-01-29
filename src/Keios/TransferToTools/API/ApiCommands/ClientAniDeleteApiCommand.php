<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientAniDeleteApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.ani.delete';

    protected $method = 'POST';

    protected $requiredArguments = ['phoneNumber'];

    public function getBody()
    {
        $requestBody['phoneNumber'] = $this->arguments['phoneNumber'];

        return json_encode($requestBody);
    }
}
