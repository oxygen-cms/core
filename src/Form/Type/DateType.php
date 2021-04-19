<?php

namespace Oxygen\Core\Form\Type;

use DateTime;
use DateTimeZone;
use Oxygen\Core\Form\FieldMetadata;

class DateType extends BaseType {

    /**
     * Transforms the given value ready for a form.
     *
     * @param FieldMetadata $metadata
     * @param mixed         $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        return ($value instanceof DateTime) ? $value->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('Y-m-d') : $value;
    }
}
