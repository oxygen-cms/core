<?php

namespace Oxygen\Core\Config;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\ConfigPublisher;

class ConfigServiceProvider extends ServiceProvider {

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
    public function register() {
        $this->app['config'] = $this->app->share(function($app) {
            $loader = $app->getConfigLoader();
            $path = $app['path'] . '/config';

            $publisher = new ConfigPublisher($app['files'], $path);
            $publisher->setPackagePath($app['path.base'] . '/vendor');

            $writer = new FileWriter(
                $app['files'],
                $loader,
                new Rewriter(),
                $publisher,
                $path
            );
            return new Repository($loader, $writer, $app['env']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            'config'
        ];
    }

}
