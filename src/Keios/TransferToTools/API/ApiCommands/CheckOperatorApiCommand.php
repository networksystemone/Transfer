<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class CheckOperatorApiCommand extends ApiCommand implements ApiCommandInterface
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
            <action>msisdn_info</action>
            <destination_msisdn>$destination_msisdn</destination_msisdn>
            <delivered_amount_info>1</delivered_amount_info>
          </xml>
XML;
    }
} 