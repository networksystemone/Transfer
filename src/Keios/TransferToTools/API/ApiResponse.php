<?php namespace Keios\TransferToTools\API;

class ApiResponse
{
    private $responseData;

    private $responseStatus;

    private $hasErrors;

    private $statusCode;

    private $errorStatusCodes = ['4', '5'];

    public function __construct($responseData, $responseStatus, $statusCode, $hasErrors = false)
    {
        $this->responseData = $responseData;
        $this->responseStatus = $responseStatus;
        $this->hasErrors = $hasErrors;
        $this->statusCode = $statusCode;

        if (in_array($statusCode[0], $this->errorStatusCodes))
            $this->hasErrors = true;

    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getData()
    {
        return $this->responseData;
    }

    public function getStatus()
    {
        return $this->responseStatus;
    }

    public function hasErrors()
    {
        return $this->hasErrors;
    }
} 