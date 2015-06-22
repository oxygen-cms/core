<?php

namespace Oxygen\Core\Action;

class AdminAction extends Action {

    const AUTH_MIDDLEWARE_NAME = 'oxygen.auth';

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
        $this->permissions = ($this->group->hasName() ? $this->group->getName() . '.' : '') . $this->name;
        $this->useSmoothState = true;
        $this->routeParametersCallback = function($action, array $options) {
            if(isset($options['model'])) {
                return [
                    $options['model']->getId()
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
        $filters = [self::AUTH_MIDDLEWARE_NAME];
        return array_merge($filters, parent::getBeforeFilters());
    }

}
