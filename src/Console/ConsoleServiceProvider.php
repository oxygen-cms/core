<?php

namespace Oxygen\Core\Console;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->commands('Oxygen\Core\Console\BlueprintListCommand');
        $this->commands('Oxygen\Core\Console\BlueprintDetailCommand');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            BlueprintListCommand::class,
            BlueprintDetailCommand::class
        ];
    }

}
