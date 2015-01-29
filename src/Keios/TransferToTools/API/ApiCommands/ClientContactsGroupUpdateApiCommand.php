<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientContactsGroupUpdateApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.contacts.group.update';

    protected $method = 'POST';

    protected $requiredArguments = ['name',];

    protected $optionalArguments = ['id', 'isShared', 'branchId'];

    public function getBody()
    {
        $requestBody['group'] = [];

        foreach ($this->optionalArguments as $argument) {
            if (array_key_exists($argument, $this->arguments))
                $requestBody['group'][$argument] = $this->arguments[$argument];
        }


        foreach ($this->requiredArguments as $argument) {
            $requestBody['group'][$argument] = $this->arguments[$argument];
        }
        return json_encode($requestBody);
    }
}
