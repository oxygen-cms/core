<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Html\RenderableInterface;

abstract class Field implements RenderableInterface {

    /**
     * The $meta property holds all of
     * the static data about the form field.
     * This class adds on to that providing
     * per-session information such as the
     * current value of the field.
     *
     * @var Oxygen\Core\Form\FieldMetadata
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
     * @param string    $value
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
     * Sets the entity associated with the field.
     *
     * @param object $entity
     */

    public function setEntity($entity) {
        $this->entity = $entity;
    }

    /**
     * Get the entity.
     *
     * @return object
     */

    public function getEntity() {
        return $this->entity;
    }

    /**
     * Renders the object.
     *
     * @param array             $arguments
     * @param RendererInterface|callable $renderer
     * @throws Exception if no renderer has been set
     * @return string the rendered object
     */

    public abstract function render(array $arguments = [], $renderer = null);

    /**
     * Create a field from meta and a model
     *
     * @param FieldMetadata $meta
     * @param object $entity
     * @return Oxygen\Core\Html\Form\Field
     */

    public static function fromEntity(FieldMetadata $meta, $entity) {
        $instance = new static($meta, $entity->getAttribute($meta->name));
        $instance->setEntity($entity);
        return $instance;
    }

}
