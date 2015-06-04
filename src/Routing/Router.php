<?php

namespace Oxygen\Core\Routing;

use Illuminate\Routing\Router as BaseRouter;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Action\Action;

class Router extends BaseRouter {

    /**
     * Generates a Route from a \Oxygen\Core\Blueprint\Blueprint
     *
     * @param Action $action
     * @return void
     */
    public function blueprint(Blueprint $blueprint) {
        foreach($blueprint->getActions() as $action) {
            $this->action($action);
        }
    }

    /**
     * Generates a Route from a Oxygen\Core\Action\Action
     *
     * @param Action $actions
     * @return void
     */
    public function action(Action $action) {
        if($action->isValid()) {
            $closure = function() use($action) {
                $route = $this->addRoute($action->getMethod(), $action->getPattern(), [
                    'as'        => $action->getName(),
                    'before'    => $action->getBeforeFilters(),
                    'after'     => $action->getAfterFilters(),
                    'uses'      => $action->getUses()
                ]);
                $callback = $action->customRouteCallback;
                $callback($action, $route);
            };

            if($action->register === 'atEnd') {
                $this->container['app']->before($closure);
            } else if($action->register) {
                $closure();
            }
        }
    }

}
