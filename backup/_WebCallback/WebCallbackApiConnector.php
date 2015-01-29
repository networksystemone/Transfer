<?php namespace Keios\TransferToTools\WebCallback;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use GuzzleHttp\Client;

class WebCallbackApiConnector
{
    // TODO
    protected $listenerFileName = 'callback.aspx.php';

    protected $path = null;

    protected $host = null;

    protected $apiKey = null;

    protected $guzzleClient;

    protected $username = null;

    protected $password = null;

    protected $guid = null;

    protected $permittedTypes = [
        'sourceFirst' => 1,
        'simultaneous' => 2,
    ];

    protected $lastStatusCode;

    protected $request;

    protected $query;

    public function authenticateBy(TransferToApiKeyInterface $apiKey)
    {
        $this->boot($apiKey);
    }

    /**
     * Create HTTP Request Client, Guzzle in this case
     * @param TransferToApiKeyInterface $apiKey
     * @return null
     */
    protected function boot(TransferToApiKeyInterface $apiKey)
    {
        $this->apiKey = $apiKey;

        if ($this->apiKey->hasGuid())
            $this->guid = $this->apiKey->getGuid();
        else {
            $this->username = $apiKey->getUsername();

            $this->password = $apiKey->getPasswordUnencrypted();
        }
        $this->guzzleClient = new Client([
            'base_url' => [$this->apiKey->getBaseUrl() . '/{path}/', ['path' => $this->apiKey->getPath()]],
        ]);
    }

    public function createCallback($source, $destination, $type)
    {
        if (!is_numeric($source) || !is_numeric($destination))
            throw new \InvalidArgumentException('Source and destination have to be valid phone numbers without any non-numeric characters');

        if (!array_key_exists($type, $this->permittedTypes))
            throw new \InvalidArgumentException('Callback type has to either "simultaneous" or "sourceFirst".');

        if (!$this->username || !$this->password)
            throw new \Exception('Username or password not set, cannot create remote callback queue.');

        $type = $this->permittedTypes[$type];

        $this->prepareRequest();

        /*
         * Auth information
         */

        $this->query->set('login', $this->username);
        $this->query->set('password', $this->password);

        /*
         * Callback parameters
         */

        $this->query->set('source', $source);
        $this->query->set('dest', $destination);
        $this->query->set('type', $type);

        return $this->sendRequest();
    }

    public function getCallbackStatus()
    {
        if (!$this->guid)
            throw new \Exception('Guid not set, cannot access remote callback queue.');

        $this->prepareRequest();

        $this->query->set('guid', $this->guid);
        $this->query->set('cmd', 'getStatus');

        return $this->sendRequest();
    }

    public function hangUp()
    {
        if (!$this->guid)
            throw new \Exception('Guid not set, cannot access remote callback queue.');

        $this->prepareRequest();

        $this->query->set('guid', $this->guid);
        $this->query->set('cmd', 'hangUp');

        return $this->sendRequest();
    }

    public function hasErrors()
    {
        $errors = false;
        if ($this->lastStatusCode[0] === '4' || $this->lastStatusCode[0] === '5')
            $errors = true;

        return $errors;
    }

    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
    }

    protected function sendRequest()
    {
        try {

            /* TO REMOVE */
            echo 'Executing ' . $this->apiKey->getPath() . '/' . $this->listenerFileName . '?' . $this->query; //todo
            /* END TO REMOVE */

            $response = $this->guzzleClient->send($this->request);
            $this->lastStatusCode = $response->getStatusCode();
            return $response->xml();
        } catch (ClientException $e) {
            return $this->handleClientException($e);
        } catch (ServerException $e) {
            return $this->handleServerException($e);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        }
    }

    protected function prepareRequest()
    {
        $this->request = $this->guzzleClient->createRequest('GET', $this->listenerFileName);
        $this->query = $this->request->getQuery();
    }

    protected function handleRequestException(RequestException $exception)
    {
        if ($exception->hasResponse()) {
            $response = $exception->getResponse();
            $this->lastStatusCode = $response->getStatusCode();
            return (string)$response->getBody();
        } else {
            /*
             * We don't know what happened, stop everything
             */
            throw $exception;
        }
    }

    protected function handleServerException(ServerException $exception)
    {
        if ($exception->hasResponse()) {
            $response = $exception->getResponse();
            $this->lastStatusCode = $response->getStatusCode();
            return (string)$response->getBody();
        } else {
            /*
             * We don't know what happened, stop everything
             */
            throw $exception;
        }
    }

    protected function handleClientException(ClientException $exception)
    {
        if ($exception->hasResponse()) {
            $response = $exception->getResponse();
            $this->lastStatusCode = $response->getStatusCode();
            return (string)$response->getBody();
        } else {
            /*
             * We don't know what happened, stop everything
             */
            throw $exception;
        }
    }

} 