<?php namespace Keios\TransferToTools\API\ApiKeys;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\TransferToApiKey;

class TransferToUserApiKey extends TransferToApiKey implements TransferToApiKeyInterface
{
    public function setCredentials($login, $token, $md5)
    {
        $this->login = $login . "#admin";
        $this->token = $token;
        $this->md5 = md5($login.$token.$keyunique);
    }

} 