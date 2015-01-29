<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class GetClientDidsApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/GetClientDIDs';

    protected $method = 'POST';

    protected $requiredArguments = ['page', 'perPage'];

    public function getBody()
    {
        $requestBody = ['pageOffset' => $this->arguments['page'], "pageSize" => $this->arguments['perPage']];
        return json_encode($requestBody);
    }
}
