<?php namespace Keios\TransferToTools;

use Illuminate\Support\ServiceProvider;
use Keios\TransferToTools\Account\Manager;
use Keios\TransferToTools\API\ApiConnector;
use Keios\TransferToTools\API\ApiKeys\TransferToAdminApiKey;
use Keios\TransferToTools\API\ApiKeys\TransferToClientApiKey;
use Keios\TransferToTools\API\RequestClients\GuzzleRequestClient;
use Keios\TransferToTools\CDR\CallDetailRecord;
use Keios\TransferToTools\WebCallback\WebCallbackApiConnector;

class TransferToToolsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('TransferTo.cdr', function ($app) {
            return new CallDetailRecord($app->make('db'), $app->make('config'));
        });

        $this->app->bind('TransferTo.account.manager', function ($app) {
            return new Manager($app->make('db'), $app->make('config'));
        });

        $this->app->bind('TransferTo.api.key.admin', function () {
            return new TransferToAdminApiKey();
        });

        $this->app->bind('TransferTo.api.key.client', function () {
            return new TransferToClientApiKey();
        });

        $this->app->bind('TransferTo.api.connector', function () {
            return new ApiConnector(new GuzzleRequestClient());
        });

        $this->app->bind('TransferTo.webcallback.connector', function () {
            return new WebCallbackApiConnector();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('TransferTo.cdr', 'TransferTo.api.key.admin', 'TransferTo.api.key.client', 'TransferTo.api.connector', 'TransferTo.account.manager');
    }

}
