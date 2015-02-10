<?php namespace Keios\TransferToTools\API\Contracts;

interface TransferToApiKeyInterface
{
    public function getLogin();

    public function getKey();

    public function getHash();

    public function setToken($token);

    public function setLogin($login);

}