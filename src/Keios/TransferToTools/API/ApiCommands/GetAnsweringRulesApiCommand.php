<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class GetAnsweringRulesApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/GetAnsweringRules';

    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        $requestBody = [];
        if (array_key_exists('didNumber', $this->arguments))
            $requestBody['dIDNumber'] = $this->arguments['didNumber'];

        if (empty($requestBody))
            $requestBody = '{}';
        else
            $requestBody = json_encode($requestBody);

        return $requestBody;
    }
}
