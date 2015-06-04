<?php

namespace Oxygen\Core\Form;

interface FieldType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string        $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value);

    /**
     * Transforms the given value into a representation ready for a form field.
     *
     * @param FieldMetadata $metadata
     * @param mixed $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value);

}
