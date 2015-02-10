<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

// Provides all recharge values for selected operator

class ListProductsApiCommand extends ApiCommand implements ApiCommandInterface
{
  protected $method = 'POST';

  protected $requiredArguments = [];

  public function getBody()
  {
    $login = $this->apiKey->getLogin();
    $key = $this->apiKey->getKey();
    $hash = $this->apiKey->getHash();
    //$operator_id = '1';

    return <<<XML
    <xml>
      <login>$login</login>
      <key>$key</key>
      <md5>$hash</md5>
      <action>pricelist</action>
      <infotype>operator</infotype>
      <content>$operator_id</content> 
    </xml>
XML;
  } //todo
} 