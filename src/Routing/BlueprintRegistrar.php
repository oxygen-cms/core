<?php

namespace Oxygen\Core\Routing;

use Illuminate\Routing\Router;
use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Contracts\Routing\BlueprintRegistrar as BlueprintRegistrarContract;

class BlueprintRegistrar implements BlueprintRegistrarContract {

    /**
     * An array of actions to be registered at the last possible moment because they override other actions.
     * eg: '/**'
     *
     * @var array
     */
    protected $registerActionsLast = [];

    protected $router;

    /**
     * Constructs the BlueprintRegistrar
     *
     * @param Router $router
     */
    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * Generates a Route from a \Oxygen\Core\Blueprint\Blueprint
     *
     * @param \Oxygen\Core\Blueprint\Blueprint $blueprint
     */
    public function blueprint(Blueprint $blueprint) {
        foreach($blueprint->getActions() as $action) {
            $this->action($action);
        }
    }

    /**
     * Generates a Route from a Oxygen\Core\Action\Action
     *
     * @param \Oxygen\Core\Action\Action $action
     */
    public function action(Action $action) {
        if($action->register === Action::REGISTER_AT_END) {
            $this->registerActionsLast[] = $action;
        } else if($action->register) {
            $this->registerAction($action);
        }
    }

    public function registerFinal() {
        foreach($this->registerActionsLast as $action) {
            $this->registerAction($action);
        }
    }

    protected function registerAction(Action $action) {
        $method = strtolower($action->getMethod());
        $route = $this->router->{$method}($action->getPattern(), [
            'as'        => $action->getName(),
            'before'    => $action->getBeforeFilters(),
            'after'     => $action->getAfterFilters(),
            'uses'      => $action->getUses()
        ]);
        $callback = $action->customRouteCallback;
        $callback($action, $route);
    }

    /**
     * Returns the underlying router
     *
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }
}
