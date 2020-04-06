<?php

namespace Oxygen\Core\Action\Factory;

use InvalidArgumentException;
use Oxygen\Core\Action\Action;
use Oxygen\Core\Factory\FactoryInterface;

class ActionFactory implements FactoryInterface {

    /**
     * Creates a new Action using the passed parameters.
     *
     * @param array  $parameters Passed parameters
     * @param string $controller Default controller to use if none is provided
     * @return mixed
     */
    public function create(array $parameters, $controller = null) {
        $parameters = $this->parseParameters($parameters, $controller);

        $action = new Action(
            $parameters['name'],
            $parameters['pattern'],
            $parameters['uses'],
            $parameters['group']
        );

        $this->setProperties($action, $parameters);

        return $action;
    }

    /**
     * Sets properties on the action from an input array.
     *
     * @param array $parameters
     * @param string $controller
     * @return array
     */
    protected function parseParameters(array $parameters, $controller) {
        if(!isset($parameters['uses']) || $parameters['uses'] === null) {
            if($controller === null) {
                throw new InvalidArgumentException('No `uses` key provided for Action');
            } else {
                $parameters['uses'] = $controller . '@' . $parameters['name'];
            }
        }

        return $parameters;
    }

    /**
     * Sets properties on the action from an input array.
     *
     * @param Action $action     Action to set the properties on
     * @param array  $properties Properties to set
     */
    protected function setProperties($action, $properties) {
        unset($properties['name'], $properties['pattern'], $properties['uses'], $properties['group']);

        foreach($properties as $key => $value) {
            $action->$key = $value;
        }
    }

}
