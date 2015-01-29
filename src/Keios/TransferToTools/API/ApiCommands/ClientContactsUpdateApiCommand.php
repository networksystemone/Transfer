<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ClientContactsUpdateApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/client.contacts.update';

    protected $method = 'POST';

    protected $requiredArguments = [];

    protected $contactVariables = [
        'id',
        'firstName',
        'lastName',
        'city',
        'country',
        'eMail',
        'state',
        'address',
        'zip',
        'homePage',
        'notes',
        'groupId'
    ];

    protected $contactArrays = [
        'phones',
        'customs',
        'ims'
    ];

    public function getBody()
    {
        $requestBody['contact'] = [];

        foreach ($this->contactVariables as $variable) {
            if (array_key_exists($variable, $this->arguments))
                $requestBody['contact'][$variable] = $this->arguments[$variable];
        }

        foreach ($this->contactArrays as $arrayName)
            if (array_key_exists($arrayName, $this->arguments) && is_array($this->arguments[$arrayName])) {
                $requestBody['contact'][$arrayName] = [];
                $requestBody['contact'][$arrayName] = $this->arguments[$arrayName];
            }

        return json_encode($requestBody);
    }
}
