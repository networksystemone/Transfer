<?php namespace Keios\Topup\Classes;

use App;
use Cache;
use Keios\Topup\Models\Settings;
use Lang;
use SoapBox\Formatter\Formatter;
use Validator;

/**
 * Transfer-to API helpers
 *
 * Sends requests to Keios TransferTo Tools
 * Caches results
 * Parses the responses.
 *
 * @package keios\topup
 * @author Jakub Zych
 */
class ApiTools
{
    // Connects to TransferTo.
    public function apiConnect()
    {
        $key = App::make('transferto.api.key.user');
        $key->setLogin(Settings::get('login'));
        $key->setToken(Settings::get('token'));
        $apiConnector = App::make('transferto.api.connector');
        $apiConnector->authenticateBy($key);
        return $apiConnector;
    }

    // Parses XML response
    private static function parseCommand($responseXML){
        $formatter = Formatter::make($responseXML, Formatter::XML);
        $apiResponse = $formatter->toArray();
        return $apiResponse;
    }

    // Sends command and return array of all available countries and their ids
    public function apiListCountries()
    {
        $response = Cache::remember('topup.allCountries', 1800, function () {
            $apiConnector = $this->apiConnect();
            $responseXML = $apiConnector->listCountries();
            $apiResponse = $this->parseCommand($responseXML);
            return $apiResponse;
        });
        return $response;
    }

    // Sends mobile number and receives information about its operator
    public function apiCheckOperator($destinationMsisdn)
    {
        $apiConnector = $this->apiConnect();
        $responseXML = $apiConnector->checkOperator(['destination' => $destinationMsisdn]);
        $response = $this->parseCommand($responseXML);
        return $response;
    }

    // Triggers a topup procedure
    public function apiConductTopup($senderNumber, $smsMessage, $phoneNumber, $rechargeValue, $cid, $senderSMS, $senderText)
    {
        $apiConnector = $this->apiConnect();
        $responseXML = $apiConnector->conductTopUp([
            'number' => $senderNumber,
            'smsMessage' => $smsMessage,
            'destinationMsisdn' => $phoneNumber,
            'rechargeValue' => $rechargeValue,
            'cid' => $cid,
            'senderSMS' => $senderSMS,
            'senderText' => $senderText]);
        $response = $this->parseCommand($responseXML);
        return $response;
    }

    // If topup is PIN bases, it requires additional actions
    public function processPINTopup(){
        //todo
    }

    // Lists all operators for given country ID
    public function apiListOperators($countryCode)
    {
        $response = Cache::remember('topup.operators.in.' . trim($countryCode), 1800, function () use($countryCode){
            $apiConnector = $this->apiConnect();
            $responseXML = $apiConnector->listOperators(['countryid' => $countryCode]);
            $apiResponse = $this->parseCommand($responseXML);
            return $apiResponse;
        });
        return $response;
    }

    // Reserve Transaction ID
    public function apiReserveID()
    {
        $apiConnector = $this->apiConnect();
        $responseXML = $apiConnector->reserveID();
        $response = $this->parseCommand($responseXML);
        return $response;
    }

    // Check Promotions (mobile phone based, no cache)
    public function apiCheckPromotions($destinationMsisdn)
    {
        $apiConnector = $this->apiConnect();
        $responseXML = $apiConnector->checkPromotions(['destination' => $destinationMsisdn]);
        $response = $this->parseCommand($responseXML);
        return $response;
    }

    // Check Promotions (operator id based, cached)
    public function apiCheckOperatorPromotions($operatorID)
    {
        $response = Cache::remember('topup.promotions.in.' . trim($operatorID), 1800, function () use($operatorID) {
            $apiConnector = $this->apiConnect();
            $responseXML = $apiConnector->checkPromotions(['operatorid' => $operatorID]);
            $apiResponse = $this->parseCommand($responseXML);
            return $apiResponse;
        });
        return $response;
    }

    // Check CountryID
    public function apiCheckCountryID($isoCode)
    {
        //todo validator
        $isoTools = new IsoFinder();
        $countryName = $isoTools->findName($isoCode);
        $apiResponse = $this->apiListCountries();
        $combinedList = Cache::remember('topup.countryIdList', 1800, function () use($apiResponse) {
            $countryArray = explode(",", $apiResponse["country"]);
            $codeArray = explode(",", $apiResponse["countryid"]);
            $combined = array_combine($countryArray, $codeArray);
            return $combined;
        });
        return $combinedList[$countryName];
    }

}