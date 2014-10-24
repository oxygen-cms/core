<?php

namespace Oxygen\Core;

use Illuminate\Support\ServiceProvider;

use Oxygen\Core\Blueprint\Manager as BlueprintManager;
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
        $this->package('oxygen/core', 'oxygen/core', __DIR__ . '/../resources');

        $this->app['router']->filter('oxygen.csrf', 'Oxygen\Core\Http\Filter\CsrfFilter');

        $this->app['view']->composer($this->app['paginator']->getViewName(), function($view) {
            $queryString = array_except($this->app['request']->query(), $this->app['paginator']->getPageName());
            $view->paginator->appends($queryString);
        });
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register() {
		// bind response creator
        $this->app->bindShared([
            'oxygen.notificationResponseCreator' => 'Oxygen\Core\Http\NotificationResponseCreator'
        ], function($app) {
            return new NotificationResponseCreator(
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Http\Request'],
                $app['Illuminate\Support\Facades\Response'],
                $app['Illuminate\Routing\Redirector'],
                $app['Illuminate\Routing\UrlGenerator']
            );
        });

        // bind blueprint manager
        $this->app->bindShared(['oxygen.navigation' => 'Oxygen\Core\Html\Navigation\Navigation'], function() {
            return new Navigation();
        });

        // bind blueprint manager
        $this->app->bindShared(['oxygen.blueprintManager' => 'Oxygen\Core\Blueprint\Manager'], function($app) {
            return new BlueprintManager(
                $app->make('Oxygen\Core\Html\Navigation\Navigation'),
                $app->make('Illuminate\Config\Repository'),
                $app->make('Illuminate\Routing\Router')
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
            'oxygen.notificationResponseCreator',
            'oxygen.navigation',
            'oxygen.blueprintManager'
        ];
	}

}
