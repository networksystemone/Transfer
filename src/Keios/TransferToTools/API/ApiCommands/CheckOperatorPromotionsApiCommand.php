<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

class CheckOperatorPromotionsApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        $login = $this->apiKey->getLogin();
        $key = $this->apiKey->getKey();
        $hash = $this->apiKey->getHash();
        $operatorID = $this->arguments['operatorid'];
        //$destination_msisdn = '+48663521642';  // if checking with phone number
        //$operator_id = 1;                      // if checking with operator id

        return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <action>msisdn_info</action>
            <destination_msisdn></destination_msisdn>
            <delivered_amount_info>0</delivered_amount_info>
            <operatorid>$operatorID</operatorid>
            <return_promo>1</return_promo>
          </xml>
XML;
    }
} 