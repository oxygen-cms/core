<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableTrait;

class SubmitToolbarItem implements ToolbarItem {

    use RenderableTrait;

    public $label;

    public $color;

    public function __construct($label, $color = 'green') {
        $this->label = $label;
        $this->color = $color;
    }

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */
    public function getIdentifier() {
        return 'submit';
    }

    /**
     * Determines if the ToolbarItem should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */
    public function shouldRender(array $arguments = []) {
        return true;
    }

}
