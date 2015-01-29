<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class UpdateAnsweringRuleApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/UpdateAnsweringRule';

    protected $method = 'POST';

    protected $requiredArguments = [];

    protected $optionalArguments = [
        'beforeRule' =>
            [
                'id', 'didNumber', 'actionType', 'dndAction', 'forwardToNumber', 'greeting', 'playInLoop', 'priority'
            ],
        'callRule' =>
            [
                'id', 'didNumber', 'whenBusy', 'whenNoAnswer', 'whenOffline', 'actionType', 'forwardToNumber', 'greeting', 'playInLoop', 'priority'
            ],
    ];

    public function getBody()
    {
        $requestBody = [];

        foreach ($this->optionalArguments as $ruleType => $ruleParameters) {
            if (array_key_exists($ruleType, $this->arguments)) {
                if (is_array($this->arguments[$ruleType])) {
                    $requestBody[$ruleType] = [];
                    foreach ($ruleParameters as $ruleParam) {
                        if (array_key_exists($ruleParam, $this->arguments[$ruleType]))
                            $requestBody[$ruleType][$ruleParam] = $this->arguments[$ruleType][$ruleParam];
                    }
                } else {
                    $requestBody[$ruleType] = $this->arguments[$ruleType];
                }
            }

        }

        return json_encode($requestBody);
    }
}
