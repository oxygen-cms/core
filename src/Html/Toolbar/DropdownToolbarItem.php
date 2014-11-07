<?php

namespace Oxygen\Core\Html\Toolbar;

use InvalidArgumentException;

use Oxygen\Core\Html\RenderableTrait;

class DropdownToolbarItem extends ToolbarItem {

    use RenderableTrait;

    /**
     * The list of items
     *
     * @var array
     */

    public $items;

    /**
     * The list of items that have passed the shouldRender() test.
     *
     * @var array
     */

    public $itemsToDisplay;

    /**
     * Color of the button.
     *
     * @var string
     */

    public $color;

    /**
     * The label of the dropdown button
     *
     * @var array
     */

    public $label;

    /**
     * Icon of the dropdown button.
     *
     * @var string
     */

    public $icon;

    /**
     * Unique identifier of the dropdown.
     *
     * @var string
     */

    public $identifier;

    /**
     * Constructs the DropdownToolbarItem.
     *
     * @param string $label
     * @param array $items
     */

    public function __construct($label, array $items = []) {
        $this->items          = $items;
        $this->itemsToDisplay = [];
        $this->label          = $label;
        $this->color          = 'white';
        $this->icon           = 'angle-down';
        $this->identifier     = camel_case($this->label);
    }

    /**
     * Adds an item to the DropdownToolbarItem.
     *
     * @param ToolbarItem $item
     */

    public function addItem(ToolbarItem $item) {
        $this->items[] = $item;
    }

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */

    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Determines if the button should be rendered.
     *
     * @param array $options
     * @return boolean
     */

    public function shouldRender(array $options = []) {
        foreach($this->items as $item) {
            if($item->shouldRender($options)) {
                $this->itemsToDisplay[] = $item;
            }
        }

        return !empty($this->itemsToDisplay); // if there aren't any items in the dropdown then we won't display it
    }

}
