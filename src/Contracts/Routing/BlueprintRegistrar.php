<?php

namespace Oxygen\Core\Contracts\Routing;

use Illuminate\Routing\Router;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Blueprint\Blueprint;

interface BlueprintRegistrar {

    /**
     * Registers routes from a \Oxygen\Core\Blueprint\Blueprint
     *
     * @param Blueprint $blueprint
     */
    public function blueprint(Blueprint $blueprint);

    /**
     * Registers routes marked with 'register' => REGISTER_AT_END
     *
     * @param Blueprint $blueprint
     */
    public function blueprintFinal(Blueprint $blueprint);

    /**
     * Generates a Route from a Oxygen\Core\Action\Action
     *
     * @param Action $action
     */
    public function action(Action $action);

    /**
     * Returns the underlying router
     *
     * @return Router
     */
    public function getRouter();

}
