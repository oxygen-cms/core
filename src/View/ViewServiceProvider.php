<?php

namespace Oxygen\Core\View;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register() {
        $this->registerDbBladeEngine($this->app['view.engine.resolver']);

        $this->registerFactory();
    }

    /**
     * Register the StringBladeCompiler implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */

    public function registerDbBladeEngine($resolver) {
        $app = $this->app;

        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.

        $app->bindShared('blade.string.compiler', function($app) {
            $cache = $app['path.storage'] . '/views';

            return new BladeStringCompiler($app['files'], $app['blade.compiler'], $cache);
        });

        $resolver->register('blade.string', function() use ($app) {
            return new CompilerEngine($app['blade.string.compiler'], $app['files']);
        });
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory() {
        $this->app->bindShared('view', function($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $env = new Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $env->setContainer($app);

            $env->share('app', $app);

            return $env;
        });
    }

}
