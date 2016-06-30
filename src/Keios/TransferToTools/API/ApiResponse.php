<?php namespace Keios\TransferToTools\API;

/**
 * Class ApiResponse
 * @package Keios\TransferToTools\API
 */
class ApiResponse
{
    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * @var string
     */
    private $xmlString;

    /**
     * ApiResponse constructor.
     *
     * @param string $xml
     */
    public function __construct($xml, $httpCode)
    {
        $this->xmlString = $xml;
        $this->xml = new \SimpleXMLElement($xml);
        $this->httpCode = $httpCode;
    }

    /**
     * @return \SimpleXMLElement[]
     */
    public function getStatusCode()
    {
        $errorCodes = new \stdClass();
        $errorCodes->apiStatusCode = $this->xml->error_code;
        $errorCodes->httpStatusCode = $this->httpCode;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->xml->error_txt;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if ($this->xml->error_code != 0) {
            return true;
        }
        if ($this->httpCode != 200) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return (array)$this->xml;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode((array)$this->xml);
    }

    /**
     * @return string
     */
    public function toXmlString()
    {
        return $this->xmlString;
    }

    /**
     * @return \SimpleXMLElement
     */
    public function toSimpleXmlElement()
    {
        return $this->xml;
    }

} 