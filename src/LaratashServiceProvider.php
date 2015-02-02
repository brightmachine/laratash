<?php namespace Laratash;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewFinderInterface;

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
        $config = __DIR__ . '/config/config.php';
        $this->mergeConfigFrom($config, 'laratash');
    }

    private function registerMustacheEngine()
    {
        $this->app->bind('mustache.engine', function() {
            return $this->app->make('Laratash\MustacheEngine');
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
