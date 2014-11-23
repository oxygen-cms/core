<?php

namespace Oxygen\Core\Html\Form;

use DateTime;
use Oxygen\Core\Form\Field as FieldMeta;

abstract class Field {

    /**
     * The $meta property holds all of
     * the static data about the form field.
     * This class adds on to that providing
     * per-session information such as the
     * current value of the field.
     *
     * @var Oxygen\Core\Form\Field
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
     * @param FieldMeta $meta
     * @param string    $value
     */

    public function __construct(FieldMeta $meta, $value = '') {
        $this->meta = $meta;
        $this->value = $value;
    }

    /**
     * Returns the metadata about the form field.
     *
     * @return FieldMeta
     */

    public function getMeta() {
        return $this->meta;
    }

    /**
     * Get the value of the field.
     *
     * @return string
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
     * Create a field from meta and a model
     *
     * @param FieldMeta $meta
     * @param object $entity
     * @return Oxygen\Core\Html\Form\Field
     */

    public static function fromEntity(FieldMeta $meta, $entity) {
        $instance = new static($meta, $entity->getAttribute($meta->name));
        $instance->setEntity($entity);
        return $instance;
    }

}
