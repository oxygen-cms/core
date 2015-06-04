<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class NumberType extends BaseType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        return (int) $value;
    }

}
