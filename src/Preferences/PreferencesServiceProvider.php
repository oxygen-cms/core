<?php

namespace Oxygen\Core\Preferences;

use Oxygen\Data\BaseServiceProvider;
use Oxygen\Core\Preferences\Loader\Database\DoctrinePreferenceRepository;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Core\Theme\CurrentThemeLoader;

class PreferencesServiceProvider extends BaseServiceProvider {

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
        $this->app[PreferencesManager::class]->loadDirectory(__DIR__ . '/../resources/preferences');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'oxygen/preferences');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadRoutesFrom(__DIR__ . '/../resources/routes.php');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
        $this->loadEntitiesFrom(__DIR__ . '/Loader/Database');

        $this->app->bind(CurrentThemeLoader::class, PreferencesCurrentThemeLoader::class); // from `oxygen/theme`

        $this->app->bind(PreferenceRepositoryInterface::class, DoctrinePreferenceRepository::class);

	    $this->app->singleton(PreferencesManager::class, function() {
	        return new PreferencesManager();
	    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */

	public function provides() {
		return [
            PreferencesManager::class,
            PreferenceRepositoryInterface::class,
            CurrentThemeLoader::class
		];
	}

}
