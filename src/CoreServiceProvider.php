<?php

namespace Oxygen\Core;

use Illuminate\Support\ServiceProvider;

use Oxygen\Core\Blueprint\BlueprintManager as BlueprintManager;
use Oxygen\Core\Http\NotificationResponseCreator;
use Oxygen\Core\Html\Navigation\Navigation;

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
            __DIR__ . '/../resources/config/config.php' => config_path('oxygen/core.php'),
            __DIR__ . '/../resources/lang' => base_path('resources/views/vendor/oxygen/core')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../resources/config/config.php', 'oxygen.core');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'oxygen/core');

        $this->app['router']->middleware('oxygen.csrf', 'Oxygen\Core\Http\Filter\CsrfFilter');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register() {
        // bind blueprint manager
        $this->app->singleton(['Oxygen\Core\Html\Navigation\Navigation'], function() {
            return new Navigation();
        });

        // bind blueprint manager
        $this->app->singleton(['Oxygen\Core\Blueprint\Manager'], function() {
            return new BlueprintManager(
                $this->app->make('Oxygen\Core\Html\Navigation\Navigation'),
                $this->app->make('Illuminate\Contracts\Config\Repository'),
                $this->app->make('Illuminate\Contracts\Routing\Registrar')
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
            'Oxygen\Core\Blueprint\Manager',
            'Oxygen\Core\Html\Navigation\Navigation',
            'Oxygen\Core\Http\NotificationResponseCreator'
        ];
	}

}
