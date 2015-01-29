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

        $this->app->bind('transferto.api.key.user', function () {
            return new TransferToUserApiKey();
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
