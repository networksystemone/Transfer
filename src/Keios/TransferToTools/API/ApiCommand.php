<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Contracts\ApiCommandInterface;
use Keios\TransferToTools\API\Exceptions\InvalidImplementationException;
use InvalidArgumentException;

abstract class ApiCommand implements ApiCommandInterface
{
    protected $apiSubUrl = null;

    protected $method = null;

    protected $arguments;

    protected $requiredArguments = [];

    /**
     * Create api command
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments[0];
        $this->validateArguments();
    }

    /**
     * Returns API sub url for request
     * @return string
     * @throws InvalidImplementationException
     */
    public function getApiSubUrl()
    {
        if (is_null($this->apiSubUrl))
            throw new InvalidImplementationException('Api Sub Url has not been set for this Api Command: ' . get_class($this));

        return $this->apiSubUrl;
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
     * TO IMPLEMENT IN INHERITING CLASSES
     * @return null
     */
    public function getBody()
    {
        return null;
    }

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