<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientSpeedDialListApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.speedial.list';

    protected $method = 'POST';

    protected $requiredArguments = ['page', 'perPage',];

    protected $optionalArguments = ['speedDial', 'nickName', 'phoneNumber'];

    public function getBody()
    {
        $requestBody = [];

        foreach ($this->optionalArguments as $argument) {
            if (array_key_exists($argument, $this->arguments))
                $requestBody[$argument] = $this->arguments[$argument];
        }

        $requestBody['pageOffset'] = $this->arguments['page'];
        $requestBody['pageSize'] = $this->arguments['perPage'];

        return json_encode($requestBody);
    }
}
