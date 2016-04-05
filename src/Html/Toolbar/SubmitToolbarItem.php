<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableTrait;

class SubmitToolbarItem implements ToolbarItem {

    use RenderableTrait;

    public $label;

    public $color;

    public $stretch;

    /**
     * Dialog object to be displayed.
     */
    public $dialog;

    public function __construct($label, $color = 'green') {
        $this->label = $label;
        $this->color = $color;
        $this->stretch = false;
        $this->dialog = null;
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
     * Returns whether the ToolbarItem has a dialog.
     *
     * @return boolean
     */
    public function hasDialog() {
        return $this->dialog !== null;
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
