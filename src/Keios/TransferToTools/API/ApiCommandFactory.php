<?php namespace Keios\TransferToTools\API;

use Keios\TransferToTools\API\Exceptions\InvalidApiCommandException;

class ApiCommandFactory
{
    protected $aliases;

    protected $apiCommandsNamespace = 'Keios\TransferToTools\API\ApiCommands';

    public function __construct()
    {
        $this->aliases = require 'aliases.php';
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function make($commandName, array $arguments)
    {
        if (!array_key_exists($commandName, $this->aliases))
            throw new InvalidApiCommandException('Api command ' . $commandName . ' not found.');

        $className = '\\' . $this->apiCommandsNamespace . '\\' . $this->aliases[$commandName] . 'ApiCommand';

        if (!class_exists($className))
            throw new InvalidApiCommandException('Api command class ' . $className . ' does not exist.');

        return new $className($arguments);

    }
}