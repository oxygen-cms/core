<?php

namespace Oxygen\Core;

use Illuminate\Support\ServiceProvider;
use Oxygen\Core\Blueprint\BlueprintManager as BlueprintManager;
use Oxygen\Core\Contracts\CoreConfiguration;
use Oxygen\Core\Contracts\Routing\BlueprintRegistrar as BlueprintRegistrarContract;
use Oxygen\Core\Html\Navigation\Navigation;
use Oxygen\Core\Routing\BlueprintRegistrar;

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

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'oxygen/core');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register() {
        // bind blueprint manager
        $this->app->singleton(Navigation::class, function () {
            return new Navigation();
        });

        // bind blueprint manager
        $this->app->singleton(BlueprintManager::class, function () {
            return new BlueprintManager(
                $this->app->make(Navigation::class),
                $this->app->make(CoreConfiguration::class)
            );
        });

        $this->app->bind(BlueprintRegistrarContract::class, BlueprintRegistrar::class);

        $this->app->singleton('oxygen.layout', function () {
            return $this->app[CoreConfiguration::class]->getAdminLayout();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides() {
        return [
            BlueprintManager::class,
            Navigation::class,
            ResponseFactoryContract::class,
            ExtendedResponseFactoryContract::class
        ];
    }

}
