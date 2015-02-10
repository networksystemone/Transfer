<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class PingApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        return <<<XML
        	<xml>
  				<login>$this->apiKey->getLogin()</login>
  				<key>$this->apiKey->getKey()</key>
  				<md5>$this->apiKey->getHash()</md5>
  				<action>ping</action>
			</xml>
			XML;
    }
} 