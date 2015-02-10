<?php namespace Keios\TransferToTools;

use Illuminate\Support\ServiceProvider;
use Keios\TransferToTools\API\ApiConnector;
use Keios\TransferToTools\API\ApiKeys\TransferToApiKey;
use Keios\TransferToTools\API\RequestClients\GuzzleRequestClient;

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

        $this->app->bind('transferto.api.key.user', function () {
            return new TransferToApiKey();
        });

        $this->app->bind('transferto.api.connector', function () {
            return new ApiConnector(new GuzzleRequestClient());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('transferto.api.key.user', 'transferto.api.connector');
    }

}
