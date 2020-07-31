<?php


namespace Oxygen\Core\Templating;

use Illuminate\Support\ServiceProvider;
use Oxygen\Core\Templating\TwigTemplateValidator;

class TemplatingServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot() {
        $this->app['validator']->extend('twig_template', TwigTemplateValidator::class . '@validate');
        $this->app['validator']->replacer('twig_template', TwigTemplateValidator::class . '@replace');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(TwigTemplateCompiler::class, function ($app) {
            $cachePath = storage_path('twig');

            return new TwigTemplateCompiler($cachePath);
        });

        $this->app->singleton(TwigTemplateValidator::class, function($app) {
            return new TwigTemplateValidator($app[TwigTemplateCompiler::class]);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [
            'validator',
            TwigTemplateCompiler::class,
            TwigTemplateValidator::class
        ];
    }

}