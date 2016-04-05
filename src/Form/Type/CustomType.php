<?php

namespace Oxygen\Core\Form\Type;

use Oxygen\Core\Form\FieldMetadata;

class CustomType extends BaseType {

    /**
     * Constructs the CustomType
     *
     * @param callable $input
     * @param callable $output
     */
    public function __construct(callable $input, callable $output) {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string        $value
     * @return mixed
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        $callable = $this->input;

        return $callable($metadata, $value);
    }

    /**
     * Transforms the given value into a human readable representation suitable for output.
     *
     * @param FieldMetadata $metadata
     * @param mixed         $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        $callable = $this->output;

        return $callable($metadata, $value);
    }

}
