<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientSpeedDialUpdateApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.speedial.update';

    protected $method = 'POST';

    protected $requiredArguments = [];

    protected $optionalArguments = ['id', 'idClient', 'clientType', 'phoneNumber', 'nickName', 'speedDial'];

    public function getBody()
    {
        $requestBody['item'] = [];
        foreach ($this->optionalArguments as $argument) {
            if (array_key_exists($argument, $this->arguments))
                $requestBody['item'][$argument] = $this->arguments[$argument];
        }

        return json_encode($requestBody);
    }
}
