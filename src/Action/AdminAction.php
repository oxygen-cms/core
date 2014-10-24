<?php

namespace Oxygen\Core\Action;

use InvalidArgumentException;

use Oxygen\Core\Blueprint\Blueprint;

class AdminAction extends Action {

    const PERMISSIONS_FILTER_NAME = 'oxygen.permissions';
    const AUTH_FILTER_NAME = 'oxygen.auth';
    const CSRF_FILTER_NAME = 'oxygen.csrf';

    /**
     * Constructs an Action.
     *
     * @param string $name
     * @param string $pattern
     * @param mixed $uses
     * @param Group $group
     */

    public function __construct($name, $pattern, $uses, Group $group = null) {
        parent::__construct($name, $pattern, $uses, $group);
        $this->permissions = ($this->group->hasName() ? $this->group->name . '.' : '') . $this->name;
        $this->useSmoothState = true;
        $this->routeParametersCallback = function($action, array $options) {
            if(isset($options['model'])) {
                return [
                    $options['model']->getKey()
                ];
            } else {
                return [];
            }
        };
    }

    /**
     * Get the $beforeFilters array.
     *
     * @return array
     */

    public function getBeforeFilters() {
        $filters = [self::AUTH_FILTER_NAME];
        if($this->method !== Action::METHOD_GET) {
            $filters[] = self::CSRF_FILTER_NAME;
        }
        return array_merge($filters, parent::getBeforeFilters());
    }

}