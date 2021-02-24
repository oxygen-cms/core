<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class ListType extends BaseType {

    /**
     * Transforms the given value ready for a form.
     *
     * @param FieldMetadata $metadata
     * @param mixed         $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        return implode(', ', $value);
    }
}
