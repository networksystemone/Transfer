<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class UpdateClientPersonalApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/UpdateClientPersonal';

    protected $method = 'POST';

    protected $requiredArguments = [];

    protected $optionalArguments = ['firstName', 'lastName', 'country', 'state', 'zip', 'city', 'address', 'taxId', 'eMail', 'phoneNumber', 'mobileNumber'];

    public function getBody()
    {
        $personalData = [];
        foreach ($this->optionalArguments as $argument) {
            if (array_key_exists($argument, $this->arguments))
                $personalData[$argument] = $this->arguments[$argument];
        }

        $requestBody = [
            "personal" => $personalData
        ];
        return json_encode($requestBody);
    }
}
