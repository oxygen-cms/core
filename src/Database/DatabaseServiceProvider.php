<?php

namespace Oxygen\Core\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider {

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
        $this->app['db']->connection()->getSchemaBuilder()->blueprintResolver(function($table, $callback) {
            return new Blueprint($table, $callback);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides() {
        return [];
    }

}
