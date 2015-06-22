<?php

namespace Oxygen\Core\Contracts\Routing;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Blueprint\Blueprint;
use Illuminate\Contracts\Routing\Registrar as BaseRegistrar;

interface Registrar extends BaseRegistrar {

    /**
     * Generates a Route from a \Oxygen\Core\Blueprint\Blueprint
     *
     * @param \Oxygen\Core\Blueprint\Blueprint $blueprint
     */
    public function blueprint(Blueprint $blueprint);

    /**
     * Generates a Route from a Oxygen\Core\Action\Action
     *
     * @param \Oxygen\Core\Action\Action $action
     */
    public function action(Action $action);

    /**
     * Set a global where pattern on all routes.
     *
     * @param  string  $key
     * @param  string  $pattern
     * @return void
     */
    public function pattern($key, $pattern);

}
