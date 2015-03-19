<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

// Responsible for conducting the top up

class ConductTopUpApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
        $login = $this->apiKey->getLogin();
        $key = $this->apiKey->getKey();
        $hash = $this->apiKey->getHash();
        $msisdn = $this->arguments['number'];
        $destinationMsisdn = $this->arguments['destinationMsisdn'];
        $rechargeValue = $this->arguments['rechargeValue'];
        $smsMessage = $this->arguments['sms'];
        //$cid = $this->arguments['cid'];
        //$senderSMS = $this->arguments['senderSMS'];
        //$senderText = $this->arguments['senderText'];

        return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <msisdn>$msisdn</msisdn>
            <sms>$smsMessage</sms>
            <destination_msisdn>$destinationMsisdn</destination_msisdn>
            <product>$rechargeValue</product>
            <cid1></cid1>
            <sender_sms></sender_sms>
            <sender_text></sender_text>
            <delivered_amount_info>1</delivered_amount_info>
            <return_promo>1</return_promo>
            <return_timestamp>1</return_timestamp>
            <return_version>1</return_version>
            <return_service_fee>1</return_service_fee>
            <action>simulation</action>
          </xml>
XML;
    }
} 