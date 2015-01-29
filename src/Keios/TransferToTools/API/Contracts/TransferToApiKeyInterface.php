<?php namespace Keios\TransferToTools\API\Contracts;

interface TransferToApiKeyInterface
{
    public function setLogin($login);

    public function setToken($token);

    public function getHash();
}