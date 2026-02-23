<?php

namespace Oxygen\Core;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Oxygen\Core\Content\ObjectLinkRegistry;
use Oxygen\Core\Contracts\CoreConfiguration;
use Oxygen\Core\Contracts\Routing\ResponseFactory as ExtendedResponseFactoryContract;
use Oxygen\Core\Contracts\StaticCoreConfiguration;
use Oxygen\Core\Routing\ResponseFactory;
use Oxygen\Preferences\PreferencesManager;

class CoreServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang/vendor/oxygen/core')
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../resources/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'oxygen/core');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(CoreConfiguration::class, StaticCoreConfiguration::class);

        $this->app->singleton(ObjectLinkRegistry::class, ObjectLinkRegistry::class);

        $this->app->singleton(ResponseFactoryContract::class, ResponseFactory::class);
        $this->app->singleton(ExtendedResponseFactoryContract::class, ResponseFactory::class);
        $this->app->bind(ResponseFactory::class, function($app) {
            // lazy load stuff
            return new ResponseFactory(
                $app['view'],
                $app['redirect'],
                $app['url'],
                request(),
                function() {
                    return $this->app[PreferencesManager::class]->get('user.pageLoad::smoothState.enabled', true);
                }
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            ObjectLinkRegistry::class,
            CoreConfiguration::class,
            ResponseFactoryContract::class,
            ExtendedResponseFactoryContract::class,
            ResponseFactory::class
        ];
    }

}
