<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RendererInterface;

abstract class ToolbarItem {

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */

    public abstract function getIdentifier();

    /**
     * Determines if the ToolbarItem should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */

    public abstract function shouldRender(array $arguments = array());

    /**
     * Renders the object.
     *
     * @param array $arguments
     * @param RendererInterface $renderer
     * @return string the rendered object
     */

    public abstract function render(array $arguments = [], RendererInterface $renderer = null);

}