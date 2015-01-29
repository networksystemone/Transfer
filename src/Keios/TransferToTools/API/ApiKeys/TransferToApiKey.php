<?php namespace Keios\TransferToTools\API\ApiKeys;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\NoCredentialsException;
use InvalidArgumentException;
use Cache;
use DateTime;

class TransferToApiKey implements TransferToApiKeyInterface
{
    protected $login = null;

    protected $token = null;

    protected $uniqueKey = null;

    public function __construct(){

        $datetime = new DateTime();
        $this->uniqueKey=$datetime->getTimestamp();

    }

    public function setLogin($login){
        $this->login=$login;
    }

    public function setToken($token){
        $this->token=$token;
    }

    public function getHash(){
        return md5($this->login.$this->token.$this->uniqueKey);
    }
    
} 