<?php

namespace Oxygen\Core\Theme;

use Oxygen\Core\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider {

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

    public function register() {
        $this->app->bindShared(['oxygen.themeManager' => 'Oxygen\Core\Theme\ThemeManager'], function($app) {
            return new ThemeManager($app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides() {
        return [
            'oxygen.themeManager',
            'Oxygen\Core\Theme\ThemeManager'
        ];
    }

}
