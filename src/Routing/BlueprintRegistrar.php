<?php

namespace Oxygen\Core\Routing;

use Illuminate\Routing\Router;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Blueprint\Blueprint;
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
     * @param Blueprint $blueprint
     */
    public function blueprint(Blueprint $blueprint) {
        foreach($blueprint->getActions() as $action) {
            $this->action($action);
        }
    }

    /**
     * Generates a Route from a Oxygen\Core\Action\Action
     *
     * @param Action $action
     * @param bool $atEnd whether to register actions marked with REGISTER_AT_END
     */
    public function action(Action $action, $atEnd = false) {
        if($atEnd && $action->register === Action::REGISTER_AT_END) {
            $this->registerAction($action);
        } else if(!$atEnd && $action->register === true) {
            $this->registerAction($action);
        }
    }

    /**
     * Registers routes marked with 'register' => REGISTER_AT_END
     *
     * @param Blueprint $blueprint
     */
    public function blueprintFinal(Blueprint $blueprint) {
        foreach($blueprint->getActions() as $action) {
            $this->action($action, true);
        }
    }

    protected function registerAction(Action $action) {
        $methods = $action->getMethod();
        if(!is_array($methods)) {
            $methods = [$methods];
        }
        $methods = array_map(function(string $method) { return strtolower($method); }, $methods);
        $route = $this->router->match($methods, $action->getPattern(), [
            'as' => $action->getName(),
            'middleware' => $action->getMiddleware(),
            'uses' => $action->getUses()
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
