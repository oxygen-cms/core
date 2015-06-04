<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableTrait;

class DisabledToolbarItem implements ToolbarItem {

    use RenderableTrait;

    /**
     * The label of the toolbar item
     *
     * @var array
     */
    public $label;

    /**
     * Unique identifier of the toolbar item.
     *
     * @var string
     */
    public $identifier;

    /**
     * Constructs the DisabledToolbarItem.
     *
     * @param string $label
     */
    public function __construct($label) {
        $this->label = $label;
        $this->identifier = camel_case($this->label);
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
        return true;
    }

}
