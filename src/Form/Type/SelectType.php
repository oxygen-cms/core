<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class SelectType extends BaseType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string        $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        $isInt = true;
        foreach(array_keys($metadata->options) as $key => $notUsed) {
            if(!is_integer($key)) {
                $isInt = false;
            }
        }

        if($isInt) {
            return (int)$value;
        } else {
            return $value;
        }
    }

}
