<?php

namespace Oxygen\Core\Html\Toolbar\Factory;

use Oxygen\Core\Html\Toolbar\VoidButtonToolbarItem;
use Oxygen\Core\Factory\FactoryInterface;

class VoidButtonToolbarItemFactory implements FactoryInterface {

    /**
     * Creates a new toolbar item using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @return VoidButtonToolbarItem
     */

    public function create(array $parameters) {
        $toolbarItem = new VoidButtonToolbarItem($parameters['label'], $parameters['action']);
        unset($parameters['label'], $parameters['action']);
        foreach($parameters as $key => $value) {
            $toolbarItem->$key = $value;
        }
        return $toolbarItem;
    }

}
