<?php
namespace AdIntelligence\Client;

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
            __DIR__.'/Resources/config/parameter.php', 'adintelelligence'
        );
    }
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Resources/config/parameter.php' => config_path('adintelelligence.php'),
        ]);
    }
}