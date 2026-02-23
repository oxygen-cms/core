<?php

namespace Oxygen\Core\Preferences;

trait UsesViewErrorPathsFromTheme {
    /**
     * Register the error template hint paths.
     *
     * We override the original behaviour to allow themes to provide their
     * own error pages which will override the default error pages.
     *
     * @return void
     */
    protected function registerErrorViewPaths() {
        $paths = collect(config('view.paths'));
        $fallbackPaths = $paths->map(function ($path) {
            return "{$path}/errors";
        })->push(base_path('vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views'))->all();

        $errorViewPrefix = app(PreferencesManager::class)->get('appearance.site::errorViewPrefix', '');

        $themePaths = [];
        if($errorViewPrefix !== '') {
            try {
                $errorViewPrefixParts = explode('::', $errorViewPrefix);
                $hints = app('view')->getFinder()->getHints();
                foreach($hints[$errorViewPrefixParts[0]] as $pathRoot) {
                    $pathRoot = realpath($pathRoot);
                    $extraErrorPath = realpath($pathRoot . '/' . str_replace('.', '/', $errorViewPrefixParts[1]));
                    if(strpos($extraErrorPath, $pathRoot) === 0) {
                        $themePaths[] = $extraErrorPath;
                    }
                }
            } catch(\Exception $e) {
                // don't bother
            }
        }

        $paths = array_merge($themePaths, $fallbackPaths);

        app('view')->replaceNamespace('errors', $paths);
    }
}