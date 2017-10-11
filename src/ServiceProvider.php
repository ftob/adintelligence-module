<?php
namespace AdIntelligence\Client;

use AdIntelligence\Client\Models\Task;
use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use AdIntelligence\Client\Repositories\EloquentRepository;
use AdIntelligence\Client\Services\ClientService;
use AdIntelligence\Client\Services\Contracts\RequesterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class ServiceProvider
 * @package AdIntelligence\Client
 */
class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/parameters.php', 'adintelelligence'
        );

        $this->app->bind(ClientInterface::class, function ($app) {
            return new Client();
        });

        $this->app->bind(RepositoryInterface::class, function($app) {
            return new EloquentRepository(new Task());
        });


        $this->app->bind(RequesterInterface::class, function($app) {
            return new ClientService(
                $this->app->make(Client::class),
                \Storage::disk(config('adintelelligence.storage')),
                $this->app->make(RepositoryInterface::class)
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Resources/config/parameters.php' => config_path('adintelelligence.php'),
        ]);

        $this->publishes([
            __DIR__ . '/migrations' => database_path('/migrations')
        ], 'migrations');

    }
}