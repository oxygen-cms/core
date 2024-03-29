<?php

namespace Oxygen\Core\Html\Toolbar\Factory;

use Oxygen\Core\Factory\FactoryInterface;
use Oxygen\Core\Html\Toolbar\FormToolbarItem;

class FormToolbarItemFactory implements FactoryInterface {

    /**
     * Creates a new toolbar item using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @param string|null $controller Default controller to use
     * @return FormToolbarItem
     */
    public function create(array $parameters, string $controller = null) {
        $toolbarItem = new FormToolbarItem($parameters['action']);
        unset($parameters['action']);
        foreach($parameters as $key => $value) {
            $toolbarItem->$key = $value;
        }
        return $toolbarItem;
    }

}
