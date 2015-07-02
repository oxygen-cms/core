<?php

namespace Oxygen\Core\Form;

abstract class FieldSet {

    /**
     * @var array
     */
    private $cachedFields;

    /**
     * Creates the fields in the set.
     *
     * @return array
     */
    public abstract function createFields();

    /**
     * Returns the name of the title field.
     *
     * @return mixed
     */
    public abstract function getTitleFieldName();

    /**
     * Returns the fields in the set.
     *
     * @return array
     */
    public function getFields() {
        if($this->cachedFields == null) {
            $this->cachedFields = $this->createFields();
        }
        return $this->cachedFields;
    }

    /**
     * Returns a field in the set.
     *
     * @return FieldMetadata
     */
    public function getField($name) {
        return $this->getFields()[$name];
    }

    /**
     * Returns the title field.
     *
     * @return mixed
     */
    public function getTitleField() {
        return $this->getFields()[$this->getTitleFieldName()];
    }

    /**
     * Creates a new field
     *
     * @param $name
     * @param $arguments
     * @return \Oxygen\Core\Form\FieldMetadata
     */
    public function makeField($name, $arguments) {
        $field = new FieldMetadata($name);
        foreach($arguments as $key => $value) {
            $field->$key = $value;
        }
        return $field;
    }

    /**
     * Creates multiple new fields
     *
     * @param $fields
     * @return array
     */
    public function makeFields(array $fields) {
        $results = [];
        foreach($fields as $field) {
            $name = $field['name'];
            unset($field['name']);
            $results[$name] = $this->makeField($name, $field);
        }
        return $results;
    }

    /**
     * Checks if the field exists.
     *
     * @param $key the field name
     * @return bool
     */
    public function hasField($key) {
        return isset($this->getFields()[$key]);
    }

}