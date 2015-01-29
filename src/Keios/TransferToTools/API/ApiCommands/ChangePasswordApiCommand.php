<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ChangePasswordApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/ChangePassword';

    protected $method = 'POST';

    protected $requiredArguments = ['oldPassword', 'newPassword'];

    public function getBody()
    {
        $requestBody = [
            "oldPassword" => $this->arguments['oldPassword'], 'newPassword' => $this->arguments['newPassword']
        ];

        return json_encode($requestBody);
    }
}
