<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class BooleanType extends BaseType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        return $value === 'true' ?  true : false;
    }

    /**
     * Transforms the given value into a string representation.
     *
     * @param FieldMetadata $metadata
     * @param mixed $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        if($value === true) {
            return 'true';
        } else if($value === false) {
            return 'false';
        } else {
            return 'unknown';
        }
    }

}
