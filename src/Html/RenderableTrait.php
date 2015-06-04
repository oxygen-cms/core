<?php

namespace Oxygen\Core\Html;

use Exception;

trait RenderableTrait {

    /**
     * Default Renderer to be used in none is supplied in the render() function.
     *
     * @var RendererInterface|callable the renderer
     */

    protected static $defaultRenderer;

    /**
     * Returns the default Renderer for the object.
     *
     * @return RendererInterface|callable The default renderer
     */
    public static function getRenderer() {
        return static::$defaultRenderer;
    }

    /**
     * Sets the default Renderer for the object.
     *
     * @param RendererInterface|callable $renderer The default renderer
     */
    public static function setRenderer($renderer) {
        static::$defaultRenderer = $renderer;
    }

    /**
     * Renders the object.
     *
     * @param array             $arguments
     * @param RendererInterface|callable $renderer
     * @throws Exception if no renderer has been set
     * @return string the rendered object
     */
    public function render(array $arguments = [], $renderer = null) {
        if($renderer === null) {
            if(static::$defaultRenderer === null) {
                throw new Exception('No Default Renderer Exists for Class ' . get_class());
            } else {
                if(is_callable(static::$defaultRenderer)) {
                    $callable = static::$defaultRenderer;
                    static::$defaultRenderer = $callable();
                }
                $renderer = static::$defaultRenderer;
            }
        } else if(is_callable($renderer)) {
            $renderer = $renderer();
        }

        return $renderer->render($this, $arguments);
    }

}
