<?php namespace Keios\TransferToTools\API;

class XMLResponseParser
{
    public function __construct()
    {
        
    }

    public function parse($responseBody, $statusCode)
    {
        
        // make it immutable
        return new ApiResponse($responseData, $responseStatus, $statusCode, $hasErrors);
    }

} 