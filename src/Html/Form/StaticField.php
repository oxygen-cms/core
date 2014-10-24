<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Model\Model;
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
     * @param FieldMeta $field
     * @param string $value
     * @param boolean $pretty
     */

    public function __construct(FieldMeta $meta, $value = '', $pretty = false) {
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
     * Is the field pretty?
     *
     * @return boolean
     */

    public function getPresentedValue() {
        $presenter = $this->getMeta()->presenter;
        return $presenter($this->getValue(), $this->getModel());
    }

    /**
     * Create a field from meta and a model
     *
     * @param FieldMeta $meta
     * @param Model $model
     * @param boolean $pretty
     * @return Oxygen\Core\Html\Form\Field
     */

    public static function fromModel(FieldMeta $meta, Model $model, $pretty = false) {
        $instance = new static($meta, $model->getAttribute($meta->name), $pretty);
        $instance->setModel($model);
        return $instance;
    }

}