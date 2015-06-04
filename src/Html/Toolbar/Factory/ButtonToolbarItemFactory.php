<?php

namespace Oxygen\Core\Html\Toolbar\Factory;

use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;
use Oxygen\Core\Factory\FactoryInterface;

class ButtonToolbarItemFactory implements FactoryInterface {

    /**
     * Creates a new toolbar item using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @return ButtonToolbarItem
     */
    public function create(array $parameters) {
        $toolbarItem = new ButtonToolbarItem($parameters['label'], $parameters['action']);
        unset($parameters['label'], $parameters['action']);
        foreach($parameters as $key => $value) {
            $toolbarItem->$key = $value;
        }
        return $toolbarItem;
    }

}
