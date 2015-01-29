<?php namespace Keios\TransferToTools\API\Contracts;

interface TransferToApiKeyInterface
{
    public function setCredentials($login, $token, $md5);

    public function setUrl($fullUrl);

    public function setGuid($guid);

    public function hasGuid();

    public function getGuid();

    public function getBaseUrl();

    public function getUsername();

    public function getPassword();

    public function getPath();

    public function getAuthString();

    public function getPasswordUnencrypted();
}