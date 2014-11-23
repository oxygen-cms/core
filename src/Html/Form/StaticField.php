<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Form\Field as FieldMeta;
use Oxygen\Core\Html\RenderableTrait;

class StaticField extends Field {

    use RenderableTrait;

    /**
     * Rendering mode.
     *
     * @var boolean
     */

    protected $pretty;

    /**
     * Constructs the object.
     *
     * @param FieldMeta $meta
     * @param string    $value
     * @param boolean   $pretty
     */

    public function __construct(FieldMeta $meta, $value = '', $pretty = true) {
        parent::__construct($meta, $value);
        $this->pretty = $pretty;
    }

    /**
     * Is the field pretty?
     *
     * @return boolean
     */

    public function isPretty() {
        return $this->pretty;
    }

     /**
     * Get the presented value of the field.
     *
     * @return boolean
     */

    public function getPrettyValue() {
        $presenter = $this->getMeta()->outputTransformer;
        return $presenter($this->getValue(), $this->getEntity());
    }

    /**
     * Create a field from metadata and an entity
     *
     * @param FieldMeta $meta
     * @param object $entity
     * @param boolean $pretty
     * @return Field
     */

    public static function fromEntity(FieldMeta $meta, $entity, $pretty = true) {
        $instance = new static($meta, $entity->getAttribute($meta->name), $pretty);
        $instance->setEntity($entity);
        return $instance;
    }

}
