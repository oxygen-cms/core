<?php

namespace Oxygen\Core\Form;

use Closure;
use DateTime;
use Exception;

use Str;
use Carbon\Carbon;

class FieldMetadata {

    /*public static $TYPE_TEXT  = new SimpleType();
    const TYPE_TEXT_LONG    = 'text-long';
    const TYPE_TEXTAREA     = 'textarea';
    const TYPE_EDITOR_MINI  = 'editor-mini';
    const TYPE_EDITOR       = 'editor';
    const TYPE_PASSWORD     = 'password';
    const TYPE_EMAIL        = 'email';
    const TYPE_CHECKBOX     = 'checkbox';
    const TYPE_TOGGLE       = 'toggle';
    const TYPE_SELECT       = 'select';
    const TYPE_RADIO        = 'radio';
    const TYPE_DATE         = 'date';
    const TYPE_NUMBER       = 'number';
    const TYPE_TAGS         = 'tags';
    const TYPE_RELATIONSHIP = 'relationship';*/

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
        $this->editable          = $editable;
        $this->fillable          = false;
        $this->validationRules   = [];
        $this->attributes        = [];
        $this->options           = [];
        $this->inputTransformer  = $this->getDefaultInputTransformer();
        $this->outputTransformer = $this->getDefaultOutputTransformer();
    }

    /**
     * Add a validation rule.
     *
     * @param string $rule
     */

    public function addValidationRule($rule) {
        $this->validationRules[] = $rule;
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
     * Returns the input transformer to be used if none is set.
     *
     * @return Closure
     */

    public function getDefaultInputTransformer() {
        return function($value) {
            if($this->type === self::TYPE_CHECKBOX || $this->type === self::TYPE_TOGGLE) {
                $value = $value === 'true' ?  true : false;
            }

            if($this->type === self::TYPE_NUMBER) {
                $value = (int) $value;
            }

            return $value;
        };
    }

    /**
     * Returns the default output transformer to be used if none is set.
     *
     * @return Closure
     */

    public function getDefaultOutputTransformer() {
        return function($value) {
            if(($this->type === self::TYPE_TEXTAREA || $this->type === self::TYPE_EDITOR || $this->type === self::TYPE_EDITOR_MINI) && $value) {
                return '<pre><code>' . e($value) . '</code></pre>';
            }

            return $value;
        };
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
        if(isset(static::$types[$this->type])) {
            return static::$types[$this->type];
        } else if(static::$defaultType !== null) {
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
