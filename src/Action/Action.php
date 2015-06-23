<?php

namespace Oxygen\Core\Action;

use Closure;
use Exception;
use Oxygen\Core\Http\Method;
use URL;

use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;
use Oxygen\Core\Blueprint\Blueprint;

class Action {

    const PERMISSIONS_FILTER_NAME = 'oxygen.permissions';

    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_PATCH  = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_ANY    = 'ANY';

    const REGISTER_AT_END    = 'atEnd';

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
     * @var string
     */
    public $method;

    /**
     * Group of the action.
     *
     * @var Group
     */
    public $group;

    /**
     * Before filters for the action.
     *
     * @var array
     */
    public $beforeFilters;

    /**
     * After filters for the action.
     *
     * @var array
     */
    public $afterFilters;

    /**
     * Permissions required to excecute the action.
     *
     * @var string
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
     * @var boolean
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
     * @param mixed $uses
     * @param Group $group
     */
    public function __construct($name, $pattern, $uses, Group $group = null) {
        $this->pattern       = $pattern;
        $this->name          = $name;
        $this->method        = Method::GET;
        $this->group         = $group ?: new Group();
        $this->beforeFilters = [];
        $this->afterFilters  = [];
        $this->permissions   = null;
        $this->uses          = $uses;
        $this->register      = true;
        $this->routeParametersCallback = function($action, array $options) {
            return [];
        };
        $this->customRouteCallback = function($action, $route) { };
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
     * @return string
     */
    public function getMethod() {
        return strtoupper($this->method);
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
     * Returns the before filters array.
     *
     * @return array
     */
    public function getBeforeFilters() {
        $filters = $this->beforeFilters;
        if($this->usesPermissions()) {
            $filters[] = self::PERMISSIONS_FILTER_NAME . ':' . $this->getPermissions();
        }
        return $filters;
    }

    /**
     * Returns the after filters array.
     *
     * @return array
     */
    public function getAfterFilters() {
        return $this->afterFilters;
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
     * Returns a full URL to the action.
     *
     * @param array $options the options array
     * @return string the URL
     */
    public function getURL(array $options = []) {
        return URL::route($this->getName(), $this->getRouteParameters($options));
    }

    /**
     * Throws an exception when trying to set a non-existent property.
     *
     * @param string $variable name of the variable
     * @param dynamic $value value of the variable
     * @throws Exception
     */
    public function __set($variable, $value) {
        throw new Exception('Resource\Action\Action: Unknown key "' . $variable . '"');
    }

}
