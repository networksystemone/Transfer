<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

// Responsible for listing all currently supported operators in given country

class ListOperatorsApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
      $login = $this->apiKey->getLogin();
      $key = $this->apiKey->getKey();
      $hash = $this->apiKey->getHash();
      $countryID = $this->arguments['countryid'];


      return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <action>pricelist</action>
            <info_type>country</info_type>
            <content>$countryID</content>
          </xml>
XML;
    } //todo
} 