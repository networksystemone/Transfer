<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientAniUpdateApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.ani.update';

    protected $method = 'POST';

    protected $requiredArguments = ['phoneNumber', 'isDef'];

    public function getBody()
    {
        $requestBody['item'] = [
            'phoneNumber' => $this->arguments['phoneNumber'],
            'isDef' => $this->arguments['isDef']
        ];

        return json_encode($requestBody);
    }
}
