<?php

namespace Oxygen\Core\Html\Form;

use Input;

use Oxygen\Core\Model\Model;
use Oxygen\Core\Form\Field as FieldMeta;
use Oxygen\Core\Html\RenderableTrait;

class EditableField extends Field {

    use RenderableTrait;

    /**
     * Constructs the object.
     *
     * @param FieldMeta $meta
     * @param string    $value
     */

    public function __construct(FieldMeta $meta, $value = '') {
        parent::__construct($meta, $value);
    }

    /**
     * Get the value of the field.
     *
     * @return mixed
     */

    public function getValue() {
        if(Input::old($this->getMeta()->name)) {
            return Input::old($this->getMeta()->name);
        } else if($this->value !== null) {
            return parent::getValue();
        } else {
            return null;
        }
    }

    /**
     * Returns the value of the form field, encoded.
     *
     * @return string
     */

    public function getEncodedValue() {
        return htmlspecialchars($this->getValue());
    }

}
