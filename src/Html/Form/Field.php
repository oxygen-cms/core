<?php

namespace Oxygen\Core\Html\Form;

use Exception;
use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Html\RendererInterface;

abstract class Field implements RenderableInterface {

    /**
     * The $meta property holds all of
     * the static data about the form field.
     * This class adds on to that providing
     * per-session information such as the
     * current value of the field.
     *
     * @var FieldMetadata
     */

    protected $meta;

    /**
     * The value of the field.
     *
     * @var mixed
     */

    protected $value;

    /**
     * The entity associated with the form.
     *
     * @var object
     */

    protected $entity;

    /**
     * Constructs the object.
     *
     * @param FieldMetadata $meta
     * @param string        $value
     */
    public function __construct(FieldMetadata $meta, $value = '') {
        $this->meta = $meta;
        $this->value = $value;
    }

    /**
     * Returns the metadata about the form field.
     *
     * @return FieldMetadata
     */
    public function getMeta() {
        return $this->meta;
    }

    /**
     * Get the value of the field.
     *
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Returns the output value transformed through the field type's output transformer.
     *
     * @return string
     */
    public function getTransformedOutputValue() {
        return $this->getMeta()->getType()->transformOutput(
            $this->getMeta(),
            $this->getValue()
        );
    }

    /**
     * Renders the object.
     *
     * @param array                      $arguments
     * @param RendererInterface|callable $renderer
     * @throws Exception if no renderer has been set
     * @return string the rendered object
     */
    public abstract function render(array $arguments = [], $renderer = null);

}
