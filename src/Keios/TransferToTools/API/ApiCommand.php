<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Contracts\ApiCommandInterface;
use Keios\TransferToTools\API\Exceptions\InvalidImplementationException;
use Keios\TransferToTools\API\Contracts\TransferToApiKeyInterface;
use InvalidArgumentException;

abstract class ApiCommand implements ApiCommandInterface
{
    protected $apiKey;

    protected $method = null;

    protected $arguments;

    protected $requiredArguments = [];

    /**
     * Create api command
     * @param array $arguments
     */
    public function __construct(TransferToApiKeyInterface $apiKey, array $arguments)
    {
        $this->apiKey = $apiKey;
        $this->arguments = $arguments[0];
        $this->validateArguments();
    }

    /**
     * Returns HTTP method for request
     * @return string
     * @throws InvalidImplementationException
     */
    public function getMethod()
    {
        if (is_null($this->method))
            throw new InvalidImplementationException('Method has not been set for this Api Command: ' . get_class($this));

        return $this->method;
    }

    /**
     * Returns request body
     * @return null
     */
    abstract public function getBody();

    /**
     * Validate passed arguments
     * @throws InvalidArgumentException
     */
    protected function validateArguments()
    {
        foreach ($this->requiredArguments as $requiredArgument) {
            if (!array_key_exists($requiredArgument, $this->arguments))
                throw new InvalidArgumentException('Missing argument for command ' . get_class($this) . ': ' . $requiredArgument);
        }
    }
} 