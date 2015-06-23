<?php

namespace Oxygen\Core\Contracts\Routing;

use Illuminate\Routing\Router;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Blueprint\Blueprint;

interface BlueprintRegistrar  {

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
     * Registers all the 'final' routes
     */
    public function registerFinal();

    /**
     * Returns the underlying router
     *
     * @return Router
     */
    public function getRouter();

}
