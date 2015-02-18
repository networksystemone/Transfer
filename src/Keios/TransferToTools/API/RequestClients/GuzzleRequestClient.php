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

    const BASE_URL = 'https://fm.transfer-to.com';

    const PATH = 'cgi-bin/shop/topup';

    protected $guzzleClient;

    protected $validMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    protected $lastStatusCode;

    /**
     * Create HTTP Request Client, Guzzle in this case
     * @param TransferToApiKeyInterface $apiKey
     * @throws \Keios\TransferToTools\API\Exceptions\NoCredentialsException
     * @return null
     */
    public function boot()
    {
        $this->guzzleClient = new Client([
            'base_url' => self::BASE_URL,
            'defaults' => [
                'headers' => [
                    'Content-Type' => 'text/xml'
                ],
            ]
        ]);
    }

    public function processRequest($method, $body)
    {
        if (!in_array(strtoupper($method), $this->validMethods))
            throw new InvalidMethodException('Method ' . $method . ' is not a valid method for this http client.');

        $request = $this->guzzleClient->createRequest($method, self::PATH, [
            'body' => $body,
            'stream' => false
        ]);

        try {

            /* TO REMOVE
            echo 'Executing ' . $request->getUrl() . ' with request body: '; //todo
            echo $request->getBody(); //todo
            echo '<br /><br />';
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