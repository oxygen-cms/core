<?php

namespace Oxygen\Core\Form;

use Carbon\Carbon;
use Exception;
use Oxygen\Core\Support\Str;

class FieldMetadata {

    /**
     * Field name in the database.
     *
     * @var string
     */
    public $name;

    /**
     * Label of the field
     *
     * @var string
     */
    public $label;

    /**
     * Placeholder of the field
     *
     * @var string
     */
    public $placeholder;

    /**
     * Detailed description of the field
     *
     * @var string
     */
    public $description;

    /**
     * Type of the field
     *
     * @var string
     */
    public $type;

    /**
     * Editable means that the field should appear on forms.
     *
     * @var boolean
     */
    public $editable;

    /**
     * HTML attributes for the field.
     *
     * @var array
     */
    public $attributes;

    /**
     * Custom options array.
     * The usage of this array depends on the
     * type of field that is being rendered.
     *
     * @var array
     */
    public $options;

    /**
     * Datalist to be linked to the field
     *
     * @var array
     */
    public $datalist;

    /**
     * Type objects that customize the field.
     *
     * @var array
     */
    protected static $types;

    /**
     * Construct the object.
     *
     * @param string $name
     * @param string $type
     * @param bool   $editable
     */
    public function __construct($name, $type = 'text', $editable = false) {
        $this->name = $name;
        $this->label = Str::title(Str::camelToWords($name));
        $this->description = null;
        $this->placeholder = null;
        $this->type = $type;
        $this->editable = $editable;
        $this->attributes = [];
        $this->options = [];
    }

    /**
     * Determines if the field has a description.
     *
     * @return boolean
     */
    public function hasDescription() {
        return $this->description !== null;
    }

    /**
     * Throws an exception when trying to set a non-existent property.
     *
     * @param string $variable name of the variable
     * @param mixed  $value    value of the variable
     * @throws Exception
     */
    public function __set($variable, $value) {
        throw new Exception('Resource\Form\Field: Unknown key "' . $variable . '"');
    }

    /**
     * Returns the type.
     *
     * @return FieldType
     * @throws Exception if the type doesn't exist
     */
    public function getType() {
        if(isset(static::$types[$this->type])) {
            return static::$types[$this->type];
        } else {
            throw new Exception('No `FieldType` Object Set For Field Type "' . $this->type . '" And No Default Set');
        }
    }

    /**
     * Returns the evaluated options of this field.
     *
     * @return array
     */
    public function getOptions() {
        if(is_callable($this->options)) {
            return ($this->options)();
        } else {
            return $this->options;
        }
    }

    /**
     * Adds a type.
     *
     * @param string                      $name
     * @param \Oxygen\Core\Form\FieldType $type
     */
    public static function addType($name, FieldType $type) {
        static::$types[$name] = $type;
    }

}
