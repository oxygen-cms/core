<?php

namespace Oxygen\Core\Action\Factory;

use Oxygen\Core\Action\AdminAction;
use Oxygen\Core\Action\Group;
use Oxygen\Core\Factory\FactoryInterface;

class AdminActionFactory extends ActionFactory {

    /**
     * Creates a new Action using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @param string $controller Default controller to use if none is provided
     * @return mixed
     */
    public function create(array $parameters, $controller = null) {
        $parameters = $this->parseParameters($parameters, $controller);

        $action = new AdminAction(
            $parameters['name'],
            $parameters['pattern'],
            $parameters['uses'],
            $parameters['group']
        );

        $this->setProperties($action, $parameters);

        return $action;
    }

}
