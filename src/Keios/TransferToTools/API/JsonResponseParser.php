<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\DateParsers\JsonTimestampWithOffsetParser;

class JsonResponseParser
{
    protected $jsonDateParser;

    public function __construct()
    {
        $this->jsonDateParser = new JsonTimestampWithOffsetParser();
    }

    public function parse($responseBody, $statusCode)
    {
        $result = json_decode($responseBody, true);

        $responseStatus = isset($result['responseStatus']) ? $result['responseStatus'] : null;
        if (isset($result['responseStatus']))
            unset($result['responseStatus']);

        if (!is_null($responseStatus))
            $hasErrors = true;
        else
            $hasErrors = false;

        $responseData = $result;

        if (is_array($responseData))
            $responseData = $this->recursiveDateParse($responseData);

        // make it immutable
        return new ApiResponse($responseData, $responseStatus, $statusCode, $hasErrors);
    }

    protected function recursiveDateParse($array)
    {
        if (!is_array($array)) {
            if (strpos($array, 'Date(') !== false)
                return $this->jsonDateParser->parse($array);
            else
                return $array;
        }
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[$key] = $this->recursiveDateParse($value);
        }
        return $newArray;
    }

} 