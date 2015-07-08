<?php

namespace Oxygen\Core\Console;

use Illuminate\Support\ServiceProvider;
use Oxygen\Core\Database\AutomaticMigrator;

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
        $this->commands(BlueprintListCommand::class);
        $this->commands(BlueprintDetailCommand::class);
        $this->commands(FieldSetDetailCommand::class);
        $this->commands(PackageMigrateCommand::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(PackageMigrateCommand::class, function() {
            return new PackageMigrateCommand($this->app['migrator'], $this->app[AutomaticMigrator::class]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            BlueprintListCommand::class,
            BlueprintDetailCommand::class,
            FieldSetDetailCommand::class,
            PackageMigrateCommand::class
        ];
    }

}
