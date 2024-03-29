<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Html\RendererInterface;

interface ToolbarItem extends RenderableInterface {

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Determines if the ToolbarItem should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */
    public function shouldRender(array $arguments = []);

    /**
     * Renders the object.
     *
     * @param array             $arguments
     * @param RendererInterface $renderer
     * @return string the rendered object
     */
    public function render(array $arguments = [], $renderer = null);

}
