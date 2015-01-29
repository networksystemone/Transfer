<?php namespace Keios\TransferToTools\API\RequestClients;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Keios\TransferToTools\API\Contracts\RequestClientInterface;
use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\InvalidMethodException;
use GuzzleHttp\Client;

class GuzzleRequestClient implements RequestClientInterface
{
    protected $apiKey = null;

    protected $guzzleClient;

    protected $validMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    protected $lastStatusCode;

    /**
     * Create HTTP Request Client, Guzzle in this case
     * @param TransferToApiKeyInterface $apiKey
     * @throws \Keios\TransferToTools\API\Exceptions\NoCredentialsException
     * @return null
     */
    public function boot(TransferToApiKeyInterface $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->guzzleClient = new Client([
            'base_url' => [$this->apiKey->getBaseUrl() . '/{path}/', ['path' => $this->apiKey->getPath()]],
            'defaults' => [
                'auth' => [
                    $this->apiKey->getUsername(),
                    $this->apiKey->getPassword()
                ],
                'headers' => [
                    'Content-type' => 'application/json'
                ],
            ]
        ]);
    }

    public function isReady()
    {
        if (is_null($this->apiKey))
            return false;

        return true;
    }

    public function processRequest($method, $url, $body)
    {
        if (!in_array(strtoupper($method), $this->validMethods))
            throw new InvalidMethodException('Method ' . $method . ' is not a valid method for this http client.');

        $request = $this->guzzleClient->createRequest($method, $url, [
            'body' => $body,
            'stream' => false
        ]);

        try {

            /* TO REMOVE */
            echo 'Executing ' . $this->apiKey->getPath() . '/' . $url . ' with request body: '; //todo
            echo $request->getBody(); //todo
            /* END TO REMOVE */

            $response = $this->guzzleClient->send($request);
            $this->lastStatusCode = $response->getStatusCode();
            return (string)$response->getBody();

        } catch (ClientException $e) {
            return $this->handleClientException($e);
        } catch (ServerException $e) {
            return $this->handleServerException($e);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        }
    }

    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
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