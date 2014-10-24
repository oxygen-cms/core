<?php

namespace Oxygen\Core\Html;

interface RendererInterface {

    /**
     * Renders the element.
     *
     * @param object $object Object to render
     * @param array arguments Extra arguments to customize the element.
     */

    public function render($object, array $arguments);

}