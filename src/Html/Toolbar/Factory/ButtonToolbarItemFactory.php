<?php

namespace Oxygen\Core\Html\Toolbar\Factory;

use Oxygen\Core\Factory\FactoryInterface;
use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;

class ButtonToolbarItemFactory implements FactoryInterface {

    /**
     * Creates a new toolbar item using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @param string|null $controller default controller to use
     * @return ButtonToolbarItem
     */
    public function create(array $parameters, string $controller = null) {
        $toolbarItem = new ButtonToolbarItem($parameters['label'], $parameters['action']);
        unset($parameters['label'], $parameters['action']);
        foreach($parameters as $key => $value) {
            $toolbarItem->$key = $value;
        }
        return $toolbarItem;
    }

}
