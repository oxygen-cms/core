<?php


namespace Oxygen\Core\Form\Type;

use Illuminate\Support\MessageBag;
use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Form\FieldType;
use Oxygen\Data\Exception\InvalidEntityException;

class JsonType implements FieldType {

    /**
     * Takes the given input value and transforms it into a compatible value for storage.
     *
     * @param FieldMetadata $metadata
     * @param string $value
     * @return mixed
     * @throws InvalidEntityException
     */
    public function transformInput(FieldMetadata $metadata, $value) {
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch(\JsonException $e) {
            throw new InvalidEntityException(null, new MessageBag(['Invalid json']));
        }
    }

    /**
     * Transforms the given value into a representation ready for a form field.
     *
     * @param FieldMetadata $metadata
     * @param mixed $value
     * @return string
     */
    public function transformOutput(FieldMetadata $metadata, $value) {
        return json_encode($value, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
    }
}
