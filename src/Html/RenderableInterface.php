<?php

namespace Oxygen\Core\Html;

interface RenderableInterface {

    /**
     * Renders the object.
     *
     * @param array             $arguments
     * @param RendererInterface|callable $renderer
     * @throws Exception if no renderer has been set
     * @return string the rendered object
     */
    public function render(array $arguments = [], $renderer = null);

}
