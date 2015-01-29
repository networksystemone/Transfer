<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\NoCredentialsException;
use InvalidArgumentException;
use Cache;

abstract class TransferToApiKey implements TransferToApiKeyInterface
{
    protected $login = null;

    protected $token = null;

    protected $tokenUnencrypted = null;

    protected $baseUrl = null;

    protected $path = null;

    protected $guid = null;

    public function setCredentials($login, $token)
    {
        $this->login = $username;
        $this->token = sha1($token);
        $this->tokenUnencrypted = $token;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    public function setUrl($fullUrl)
    {
        if (!filter_var($fullUrl, FILTER_VALIDATE_URL))
            throw new InvalidArgumentException('API Base Url must be a valid url: ' . $fullUrl);

        $parsedUrl = parse_url($fullUrl);
        $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $path = $parsedUrl['path'];

        $this->path = rtrim($path, '/');
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function getBaseUrl()
    {
        if (is_null($this->baseUrl))
            throw new NoCredentialsException('Base Url for TransferTo API was not set.');

        return $this->baseUrl;
    }

    public function getUsername()
    {
        if (is_null($this->login))
            throw new NoCredentialsException('Login credential for TransferTo API was not set.');

        return $this->login;
    }

    public function getPassword()
    {
        if (is_null($this->token))
            throw new NoCredentialsException('Token credential for TransferTo API was not set.');

        return $this->token;
    }

    public function getPasswordUnencrypted()
    {
        if (is_null($this->tokenUnencrypted))
            throw new NoCredentialsException('Token credential for TransferTo API was not set.');

        return $this->tokenUnencrypted;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getAuthString()
    {
        if (is_null($this->login) || is_null($this->token))
            throw new NoCredentialsException('Credentials for TransferTo API were not set.');

        $hashedToken = sha1($this->token);

        return base64_encode($this->login . ':' . $hashedToken);
    }

    public function hasGuid()
    {
        if (is_null($this->guid))
            return false;
        else
            return true;
    }

    public function getGuid()
    {
        return $this->guid;
    }

} 