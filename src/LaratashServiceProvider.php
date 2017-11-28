<?php

namespace Laratash;

use Illuminate\Support\ServiceProvider;

class LaratashServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();

        $this->registerMustacheEngine();

        $this->registerMustacheViewExtension();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laratash', 'mustache.engine'];
    }

    private function setupConfig()
    {
        $config = __DIR__.'/config/config.php';
        $this->mergeConfigFrom($config, 'laratash');
    }

    private function registerMustacheEngine()
    {
        $this->app->bind('mustache.engine', function () {
            // Support for Laravel 5.5+ contract view engine interface
            $version = $this->app->version();
            if (version_compare($version, '5.5', '>=')) {
                return $this->app->make('Laratash\MustacheContractEngine');
            }

            return $this->app->make('Laratash\MustacheViewEngine');
        });
    }

    private function registerMustacheViewExtension()
    {
        $this->app['view']->addExtension(
            'mustache',
            'mustache',
            function () {
                return $this->app['mustache.engine'];
            }
        );
    }
}
