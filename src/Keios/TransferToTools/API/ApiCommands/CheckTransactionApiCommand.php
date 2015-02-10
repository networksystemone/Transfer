<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class CheckTransactionApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
      $login = $this->apiKey->getLogin();
      $key = $this->apiKey->getKey();
      $hash = $this->apiKey->getHash();

      // $start_date = 'YYYY-MM-DD';

      return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <action>trans_list</action>
            <destination_msisdn>$destination_msisdn</destination_msisdn>
            <msisdn>$source_msisdn</msisdn>
            <code>$error_code</code>
            <start_date>$start_date</start_date>
            <stop_date>$stop_date</stop_date>
          </xml>
XML;
    } //todo
} 