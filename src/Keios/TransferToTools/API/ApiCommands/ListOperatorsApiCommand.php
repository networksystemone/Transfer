<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class ListOperatorsApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
      $login = $this->apiKey->getLogin();
      $key = $this->apiKey->getKey();
      $hash = $this->apiKey->getHash();

      return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <action>pricelist</action>
            <infotype>country</infotype>
            <content>$country_id</content> 
          </xml>
XML;
    } //todo
} 