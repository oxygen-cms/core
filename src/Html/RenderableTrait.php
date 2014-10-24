<?php

namespace Oxygen\Core\Html;

use Exception;

trait RenderableTrait {

    /**
     * Default Renderer to be used in none is supplied in the render() function.
     *
     * @var RendererInterface the renderer
     */

    protected static $defaultRenderer;

    /**
     * Returns the default Renderer for the object.
     *
     * @return RenderableInterface The default renderer
     */

    public static function getRenderer() {
        return static::$defaultRenderer;
    }

    /**
     * Sets the default Renderer for the object.
     *
     * @param RenderableInterface $renderer The default renderer
     */

    public static function setRenderer(RendererInterface $renderer) {
        static::$defaultRenderer = $renderer;
    }

    /**
     * Renders the object.
     *
     * @param array $arguments
     * @param RendererInterface $renderer
     * @return string the rendered object
     */

    public function render(array $arguments = [], RendererInterface $renderer = null) {
        if($renderer === null) {
            if(static::$defaultRenderer === null) {
                throw new Exception('No Default Renderer Exists for Class ' . get_class());
            } else {
                $renderer = static::$defaultRenderer;
            }
        }

        return $renderer->render($this, $arguments);
    }

}