<?php namespace Keios\TransferToTools\API\ApiKeys;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\TransferToApiKey;

class TransferToAdminApiKey extends TransferToApiKey implements TransferToApiKeyInterface
{
    public function setCredentials($username, $password)
    {
        $this->username = $username . "#admin";
        $this->password = sha1($password);
    }

} 