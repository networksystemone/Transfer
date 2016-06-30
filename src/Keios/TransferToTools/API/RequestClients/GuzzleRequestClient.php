<?php namespace Keios\TransferToTools\API\RequestClients;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Keios\TransferToTools\API\Contracts\RequestClientInterface;
use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use Keios\TransferToTools\API\Exceptions\InvalidMethodException;
use GuzzleHttp\Client;

/**
 * Class GuzzleRequestClient
 * @package Keios\TransferToTools\API\RequestClients
 */
class GuzzleRequestClient implements RequestClientInterface
{

    /**
     *
     */
    const BASE_URL = 'https://fm.transfer-to.com';

    /**
     *
     */
    const PATH = 'cgi-bin/shop/topup';

    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var array
     */
    protected $validMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * @var
     */
    protected $lastStatusCode;

    /**
     * Create HTTP Request Client, Guzzle in this case
     *
     * @param TransferToApiKeyInterface $apiKey
     *
     * @throws \Keios\TransferToTools\API\Exceptions\NoCredentialsException
     * @return null
     */
    public function boot()
    {
        $this->guzzleClient = new Client(
            [
                'base_url' => self::BASE_URL,
                'defaults' => [
                    'headers' => [
                        'Content-Type' => 'text/xml',
                    ],
                ],
            ]
        );
    }

    /**
     * @param $method
     * @param $body
     *
     * @return string
     * @throws InvalidMethodException
     */
    public function processRequest($method, $body)
    {
        if (!in_array(strtoupper($method), $this->validMethods)) {
            throw new InvalidMethodException('Method '.$method.' is not a valid method for this http client.');
        }

        /* - for guzzle 3.9, to remove
        $request = $this->guzzleClient->createRequest($method, self::BASE_URL.'/'.self::PATH, [
            'body' => $body,
            'stream' => false
        ]);
         */
        $request = new Request(
            $method,
            self::BASE_URL.'/'.self::PATH,
            ['Content-Type' => 'text/xml; charset=UTF8'],
            $body
        );

        try {
            $response = $this->guzzleClient->send($request);
            $this->lastStatusCode = $response->getStatusCode();
            return $response->getBody()->getContents();

        } catch (ClientException $e) {
            return $this->handleClientException($e);
        } catch (ServerException $e) {
            return $this->handleServerException($e);
        } catch (RequestException $e) {
            return $this->handleRequestException($e);
        }
    }

    /**
     * @return mixed
     */
    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
    }

    /**
     * @param RequestException $exception
     *
     * @return string
     */
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

    /**
     * @param ServerException $exception
     *
     * @return string
     */
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

    /**
     * @param ClientException $exception
     *
     * @return string
     */
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