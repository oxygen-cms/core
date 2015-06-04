<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableTrait;

class SpacerToolbarItem implements ToolbarItem {

    use RenderableTrait;

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */
    public function getIdentifier() {
        return 'spacer';
    }

    /**
     * Determines if the ToolbarItem should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */
    public function shouldRender(array $arguments = array()) {
        return true;
    }

}
