<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Model\Model;
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
     * The model associated with the field.
     *
     * @var Model
     */

    protected $model;

    /**
     * Constructs the object.
     *
     * @param Oxygen\Core\Form\Field $field
     * @param string $value
     */

    public function __construct(FieldMeta $meta, $value = '') {
        $this->meta = $meta;
        $this->value = $value;
    }

    /**
     * Returns the metadata about the form field.
     *
     * @return Oxygen\Core\Form\Field
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
     * Sets the model associated with the field.
     *
     * @param Model $model
     */

    public function setModel($model) {
        $this->model = $model;
    }

    /**
     * Get model.
     *
     * @return Model
     */

    public function getModel() {
        return $this->model;
    }

    /**
     * Create a field from meta and a model
     *
     * @param FieldMeta $meta
     * @param Model $model
     * @return Oxygen\Core\Html\Form\Field
     */

    public static function fromModel(FieldMeta $meta, Model $model) {
        $instance = new static($meta, $model->getAttribute($meta->name));
        $instance->setModel($model);
        return $instance;
    }

}