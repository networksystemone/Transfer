<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\NoCredentialsException;
use InvalidArgumentException;
use Cache;

abstract class TransferToApiKey implements TransferToApiKeyInterface
{
    protected $username = null;

    protected $password = null;

    protected $passwordUnencrypted = null;

    protected $baseUrl = null;

    protected $path = null;

    protected $guid = null;

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = sha1($password);
        $this->passwordUnencrypted = $password;
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
        if (is_null($this->username))
            throw new NoCredentialsException('Username credential for TransferTo API was not set.');

        return $this->username;
    }

    public function getPassword()
    {
        if (is_null($this->password))
            throw new NoCredentialsException('Password credential for TransferTo API was not set.');

        return $this->password;
    }

    public function getPasswordUnencrypted()
    {
        if (is_null($this->passwordUnencrypted))
            throw new NoCredentialsException('Password credential for TransferTo API was not set.');

        return $this->passwordUnencrypted;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getAuthString()
    {
        if (is_null($this->username) || is_null($this->password))
            throw new NoCredentialsException('Credentials for TransferTo API were not set.');

        $hashedPassword = sha1($this->password);

        return base64_encode($this->username . ':' . $hashedPassword);
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