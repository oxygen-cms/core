<?php

namespace Oxygen\Core\Theme;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider {

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
        $this->app[Kernel::class]->pushMiddleware(BootThemeMiddleware::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(ThemeManager::class, function() {
            return new ThemeManager($this->app[CurrentThemeLoader::class]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            ThemeManager::class
        ];
    }

}
