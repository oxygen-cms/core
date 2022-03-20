<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Html\RenderableTrait;

class Toolbar {

    use RenderableTrait;

    /**
     * Pool of items that can be displayed on the toolbar.
     *
     * @var array
     */

    protected $itemsPool;

    /**
     * Ordered list of items from the pool
     *
     * @var array
     */

    protected $itemsOrdered;

    /**
     * SpacerToolbarItem instance
     *
     * @var SpacerToolbarItem
     */

    protected $spacer;

    /**
     * Default prefix to use for identifiers if none was given.
     *
     * @var string
     */

    protected $prefix;

    /**
     * Constructs the Toolbar.
     */
    public function __construct() {
        $this->itemsPool = [];
        $this->itemsOrdered = null;
        $this->spacer = new SpacerToolbarItem();
        $this->prefix = null;
    }

    /**
     * Returns the prefix.
     *
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * Sets the prefix.
     *
     * @param string $prefix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    /**
     * Sets a shared items pool for the toolbar.
     *
     * @param array $items
     * @return void
     */
    public function setSharedItemsPool(&$items) {
        $this->itemsPool = &$items;
    }

    /**
     * Adds toolbar items from a Blueprint.
     *
     * @param Blueprint $blueprint
     * @param string    $set Set of items to use
     * @return void
     */
    public function fillFromBlueprint(Blueprint $blueprint, $set) {
        $this->addItemsFromBlueprint($blueprint);
        $this->setOrder($blueprint->getToolbarOrder($set));
    }

    /**
     * Adds toolbar items from a Blueprint.
     *
     * @param Blueprint $blueprint
     * @return void
     */
    public function addItemsFromBlueprint(Blueprint $blueprint) {
        foreach($blueprint->getToolbarItems() as $item) {
            $this->addItem($item);
        }
    }

    /**
     * Adds a ToolbarItem.
     *
     * @param ToolbarItem $item
     * @return void
     */
    public function addItem(ToolbarItem $item) {
        $this->itemsPool[$item->getIdentifier()] = clone $item;
    }

    /**
     * Sets the order of items on the toolbar.
     *
     * @param array $keys
     * @return void
     */
    public function setOrder(array $keys) {
        $this->itemsOrdered = [];

        foreach($keys as $label => $value) {
            if($value === 'spacer' || $value === '|') {
                $this->itemsOrdered[] = $this->spacer;
            } elseif(is_array($value)) {
                $parts = explode(',', $label);
                if($this->hasItem($parts[0])) {
                    $dropdown = new DropdownToolbarItem($parts[1]);
                    $dropdown->button = $this->getItem($parts[0]);
                } else {
                    $dropdown = new DropdownToolbarItem($parts[0]);
                }

                foreach($value as $dropdownItem) {
                    if($this->hasItem($dropdownItem)) {
                        $dropdown->addItem($this->getItem($dropdownItem));
                    }
                }
                $this->itemsOrdered[] = $dropdown;
            } else {
                $this->itemsOrdered[] = $this->getItem($value);
            }
        }
    }

    /**
     * Determines a ToolbarItem exists.
     *
     * @param string $identifier
     * @return boolean
     */
    public function hasItem($identifier) {
        return isset($this->itemsPool[$this->prefix . '.' . $identifier]) || isset($this->itemsPool[$identifier]);
    }

    /**
     * Get a ToolbarItem.
     *
     * @param string $identifier
     * @return ToolbarItem
     */
    public function getItem($identifier) {
        if(!isset($this->itemsPool[$identifier]) && $this->prefix !== null) {
            return $this->itemsPool[$this->prefix . '.' . $identifier];
        }

        return $this->itemsPool[$identifier];
    }

    /**
     * Returns all the toolbar items.
     *
     * @return array
     */
    public function getItems() {
        return $this->itemsOrdered === null ? $this->itemsPool : $this->itemsOrdered;
    }

}
