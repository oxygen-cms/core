<?php

namespace Oxygen\Core\Action;

use Closure;
use Exception;
use Oxygen\Core\Http\Method;
use URL;

class Action {

    const PERMISSIONS_FILTER_NAME = 'oxygen.permissions';

    const REGISTER_AT_END = 'atEnd';

    /**
     * Pattern to match. (usually a URI)
     *
     * @var string
     */
    public $pattern;

    /**
     * Name of the action.
     *
     * @var string
     */
    public $name;

    /**
     * Request type of the action.
     * ie: get or post
     *
     * @var string|array
     */
    public $method;

    /**
     * Group of the action.
     *
     * @var Group
     */
    public $group;

    /**
     * Route middleware for the action.
     *
     * @var array
     */
    public $middleware;

    /**
     * Permissions required to excecute the action.
     *
     * @var string|null
     */
    public $permissions;

    /**
     * The actual command this action will call.
     * Can be a callback or a string in a
     * MyController@aMethod format.
     *
     * @var mixed
     */
    public $uses;

    /**
     * Callback to be excecuted to return parameters of the Action.
     *
     * @var Closure
     */
    public $routeParametersCallback;

    /**
     * Callback to be excecuted to customise the Action's route.
     *
     * @var Closure
     */
    public $customRouteCallback;

    /**
     * Whether to register the action right before
     * the Route is matched. This is useful for 'catch all'
     * routes that will otherwise override other routes.
     *
     * @var boolean|string
     */
    public $register;

    /**
     * Whether to load the link via AJAX.
     *
     * @var boolean
     */
    public $useSmoothState;

    /**
     * Constructs an Action.
     *
     * @param string $name
     * @param string $pattern
     * @param mixed  $uses
     * @param Group  $group
     */
    public function __construct($name, $pattern, $uses, Group $group = null) {
        $this->pattern = $pattern;
        $this->name = $name;
        $this->method = Method::GET;
        $this->group = $group ?: new Group();
        // by default all actions go through the web middleware - we have no pure API endpoints
        $this->middleware = [];
        $this->permissions = null;
        $this->uses = $uses;
        $this->register = true;
        $this->routeParametersCallback = function ($action, array $options) {
            return [];
        };
        $this->customRouteCallback = function ($action, $route) {
        };
    }

    /**
     * Returns the name of the action.
     *
     * @return string
     */
    public function getName() {
        return ($this->group->hasName() ? $this->group->getName() . '.' : '') . $this->name;
    }

    /**
     * Returns the pattern to match with.
     *
     * @return string
     */
    public function getPattern() {
        return ($this->group->hasPattern() ? $this->group->getPattern() . ($this->pattern === '/' ? '' : '/') : '') . $this->pattern;
    }

    /**
     * Returns the HTTP method.
     *
     * @return string|array
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Returns the actual command this action will call.
     *
     * @return array
     */
    public function getUses() {
        return $this->uses;
    }

    /**
     * Returns the middleware array.
     *
     * @return array
     */
    public function getMiddleware() {
        $middleware = $this->middleware;
        if($this->usesPermissions()) {
            $middleware[] = self::PERMISSIONS_FILTER_NAME . ':' . $this->getPermissions();
        }

        return $middleware;
    }

    /**
     * Determines if the action requires permissions.
     *
     * @return boolean
     */
    public function usesPermissions() {
        return $this->permissions !== null;
    }

    /**
     * Returns the permissions required to excecute the action.
     *
     * @return string
     */
    public function getPermissions() {
        return $this->permissions;
    }

    /**
     * Returns the parameters required for the route based upon the given input array.
     *
     * @param array $options the options array
     * @return array route parameters
     */
    public function getRouteParameters(array $options = []) {
        $callback = $this->routeParametersCallback;

        return $callback($this, $options);
    }

    /**
     * Throws an exception when trying to set a non-existent property.
     *
     * @param string $variable name of the variable
     * @param mixed  $value    value of the variable
     * @throws Exception
     */
    public function __set($variable, $value) {
        throw new Exception('Resource\Action\Action: Unknown key "' . $variable . '"');
    }

}
