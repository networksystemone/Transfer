<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;
use Keios\TransferToTools\API\DateParsers\JsonTimestampWithOffsetParser;
use InvalidArgumentException;

class GetVoiceMailsApiCommand extends ApiCommand implements ApiCommandInterface
{

    protected $apiSubUrl = 'json/syncreply/GetVoiceMails';

    protected $method = 'POST';

    protected $requiredArguments = ['page', 'perPage'];

    protected $optionalArguments = ['callerId', 'fromDate', 'toDate', 'onlyUnheard'];

    protected $dateConversion = ['fromDate', 'toDate'];

    public function getBody()
    {
        $requestParameters = [];
        foreach ($this->optionalArguments as $argument) {
            if (array_key_exists($argument, $this->arguments))
                $requestParameters[$argument] = $this->arguments[$argument];
        }

        foreach ($requestParameters as $key => $value) {
            if (in_array($key, $this->dateConversion)) {
                if (!$requestParameters[$key] instanceof \DateTime)
                    throw new InvalidArgumentException('Expected DateTime object for parameter ' . $key);

                $requestParameters[$key] = JsonTimestampWithOffsetParser::build($requestParameters[$key]);
            }
        }

        $requestParameters['pageOffset'] = $this->arguments['page'];
        $requestParameters['pageSize'] = $this->arguments['perPage'];

        return json_encode($requestParameters);
    }
}
