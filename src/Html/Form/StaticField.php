<?php

namespace Oxygen\Core\Html\Form;

use Exception;
use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Html\RendererInterface;

class StaticField extends Field implements CellInterface {

    /**
     * Renderers to be used in none is supplied in the render() function.
     *
     * @var array the renderers for different field types
     */

    protected static $renderers;

    /**
     * The renderer to be used for generic fields
     *
     * @var RendererInterface
     */

    protected static $fallbackRenderer;

    /**
     * Constructs the object.
     *
     * @param FieldMetadata $meta
     * @param string    $value
     */

    public function __construct(FieldMetadata $meta, $value = '') {
        parent::__construct($meta, $value);
    }

    /**
     * Create a field from metadata and an entity
     *
     * @param FieldMetadata $meta
     * @param object $entity
     * @return Field
     */

    public static function fromEntity(FieldMetadata $meta, $entity) {
        $instance = new static($meta, $entity->getAttribute($meta->name));
        $instance->setEntity($entity);
        return $instance;
    }

    /**
     * Returns the Renderer for the given field type.
     *
     * @param string $type
     * @return RendererInterface|callable The renderer
     */

    public static function getRenderer($type) {
        return static::$renderers[$type];
    }

    public static function getFallbackRenderer() {
        return static::$fallbackRenderer;
    }

    /**
     * Sets the default Renderer for the object.
     *
     * @param string $type
     * @param RendererInterface|callable $renderer The default renderer
     */

    public static function setRenderer($type, $renderer) {
        static::$renderers[$type] = $renderer;
    }

    public static function setFallbackRenderer($renderer) {
        static::$fallbackRenderer = $renderer;
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
        $type = $this->getMeta()->type;
        if($renderer === null) {
            if(!isset(static::$renderers[$type])) {
                if(static::$fallbackRenderer === null) {
                    throw new Exception('No Default Renderer Exists for Class ' . get_class());
                }
                $renderer = static::$fallbackRenderer;
            } else {
                if(is_callable(static::$renderers[$type])) {
                    $callable = static::$renderers[$type];
                    static::$renderers[$type] = $callable();
                }
                $renderer = static::$renderers[$type];
            }
        } else if(is_callable($renderer)) {
            $renderer = $renderer();
        }

        return $renderer->render($this, $arguments);
    }

}
