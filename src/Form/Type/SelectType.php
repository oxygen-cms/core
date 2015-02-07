<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class SelectType extends BaseType {

    /**
     * Transforms the given value into a human readable representation suitable for output.
     *
     * @param FieldMetadata $metadata
     * @param mixed         $value
     * @return string
     */

    public function transformEditableOutput(FieldMetadata $metadata, $value) {
        return $value;
    }

    /**
     * Transforms the given value into a human readable representation suitable for output.
     *
     * @param FieldMetadata $metadata
     * @param mixed         $value
     * @return string
     */

    public function transformStaticOutput(FieldMetadata $metadata, $value) {
        return $value;
    }

}
