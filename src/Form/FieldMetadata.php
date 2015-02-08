<?php

namespace Oxygen\Core\Form;

use Closure;
use DateTime;
use Exception;

use Str;
use Carbon\Carbon;

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
     * A `FieldType` object instance
     *
     * @var FieldType
     */

    public $typeInstance;

    /**
     * Editable means that the field should appear on forms.
     *
     * @var boolean
     */

    public $editable;

    /**
     * Fillable means that the field can be edited.
     *
     * @var boolean
     */

    public $fillable;

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
     * The default type.
     *
     * @var FieldType
     */

    protected static $defaultType;

    /**
     * Construct the object.
     *
     * @param string $name
     * @param string $type
     * @param bool   $editable
     */

    public function __construct($name, $type = 'text', $editable = false) {
        $this->name              = $name;
        $this->label             = Str::title(Str::camelToWords($name));
        $this->description       = null;
        $this->placeholder       = null;
        $this->type              = $type;
        $this->typeInstance      = null;
        $this->editable          = $editable;
        $this->fillable          = false;
        $this->attributes        = [];
        $this->options           = [];
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
     * @param mixed $value value of the variable
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
        if($this->typeInstance !== null) {
            return $this->typeInstance;
        }

        if(isset(static::$types[$this->type])) {
            $this->typeInstance = static::$types[$this->type];
            return static::$types[$this->type];
        } else if(static::$defaultType !== null) {
            $this->typeInstance = static::$defaultType;
            return static::$defaultType;
        } else  {
            throw new Exception('No `FieldType` Object Set For Field Type "' . $this->type . '" And No Default Set');
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

    /**
     * Sets the default type.
     *
     * @param \Oxygen\Core\Form\FieldType $type
     */

    public static function setDefaultType(FieldType $type) {
        static::$defaultType = $type;
    }

}
