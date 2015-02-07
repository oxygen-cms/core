<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Form\FieldType;

class BaseType implements FieldType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        return $value;
    }

    /**
     * Transforms the given value into a human readable representation suitable for output.
     *
     * @param FieldMetadata $metadata
     * @param mixed $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        return $value;
    }

}
