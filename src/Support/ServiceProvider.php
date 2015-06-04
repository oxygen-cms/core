<?php

namespace Oxygen\Core\Support;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider {

    /**
     * Register a directory of Doctrine entities.
     *
     * @param  string  $dir
     * @return void
     */
    public function entities($dir) {
        $metadata = $this->app['config']['doctrine::doctrine.metadata'];
        $metadata[] = $dir;
        $this->app['config']->set('doctrine::doctrine.metadata', $metadata);
    }

}
