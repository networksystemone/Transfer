<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientCallerIdSetApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.callerid.set';

    protected $method = 'POST';

    protected $requiredArguments = ['callerId'];

    public function getBody()
    {
        $requestBody['callerId'] = $this->arguments['callerId'];
        return json_encode($requestBody);
    }
}
