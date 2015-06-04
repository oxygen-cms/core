<?php

namespace Oxygen\Core\Form\Type;

use DateTime;
use Oxygen\Core\Form\FieldMetadata;

class DatetimeType extends BaseType {

    /**
     * Transforms the given value ready for a form.
     *
     * @param FieldMetadata $metadata
     * @param mixed $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        return ($value instanceof DateTime) ? $value->format('Y-m-d H:i:s') : $value;
    }

}
