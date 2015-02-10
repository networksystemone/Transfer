<?php namespace Keios\TransferToTools\API;

class XMLResponseParser
{
    public function __construct()
    {
        
    }

    public function parse($responseBody, $statusCode)
    {
        return $responseBody;
        // make it immutable
        //return new ApiResponse($responseData, $responseStatus, $statusCode, $hasErrors);
    }

} 