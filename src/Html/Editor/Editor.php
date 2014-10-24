<?php

namespace Oxygen\Core\Html\Editor;

use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Form\Field;

class Editor {

    use RenderableTrait;

    /**
     * A main code editor.
     *
     * @var int
     */

    const TYPE_MAIN = Field::TYPE_EDITOR;

    /**
     * A mini code editor.
     *
     * @var int
     */

    const TYPE_MINI = Field::TYPE_EDITOR_MINI;

    /**
     * Default options.
     *
     * @var array
     */

    public static $defaultOptions = [
        'language' => 'html'
    ];

    /**
     * Stylesheets that should be applied to the editor content.
     *
     * @var array
     */

    public static $defaultStylesheets = [];

    /**
     * Name of the code editor.
     *
     * @var string
     */

    public $name;

    /**
     * Value of the code editor.
     *
     * @var mixed
     */

    public $value;

    /**
     * Type of the code editor.
     *
     * @var int
     */

    public $type;

    /**
     * HTML attributes to be applied to the editor.
     *
     * @var array
     */

    public $attributes;

    /**
     * Custom options for the editor such as language and default mode.
     *
     * @var array
     */

    public $options;

    /**
     * Stylesheets that should be applied to the editor content.
     *
     * @var array
     */

    protected $stylesheets;

    /**
     * Style sets for the editor
     *
     * @var array
     */

    protected $styleSets;

    /**
     * Constructs the Editor.
     *
     * @param string     $name
     * @param mixed      $value
     * @param string     $type
     * @param array      $attributes
     * @param array      $options
     */

    public function __construct($name, $value = '', $type = self::TYPE_MAIN, array $attributes = [], array $options = []) {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->attributes = $attributes;
        $this->stylesheets = static::$defaultStylesheets;
        $this->styleSets = [];

        if(!isset($options['language'])) {
            $options['language'] = self::$defaultOptions['language'];
        }
        $this->options = $options;
    }

    /**
     * Adds a stylesheet to the list.
     *
     * @param string $name
     * @param string $url
     * @return void
     */

    public function addStylesheet($name, $url) {
        $this->stylesheets[$name] = $url;
    }

    /**
     * Removes a stylesheet from the list.
     *
     * @param string $name
     * @return void
     */

    public function removeStylesheet($name) {
        unset($this->stylesheets[$name]);
    }

    /**
     * Retrieves all stylesheets.
     *
     * @return array
     */

    public function getStylesheets() {
        return $this->stylesheets;
    }

    /**
     * Returns the JavaScript code used to
     * initialise a Editor for the given information.
     *
     * @return string
     */

    public function getCreateScript() {
        $text = '<script>';
        $text .= 'editors = ( typeof editors != "undefined" && editors instanceof Array ) ? editors : [];';
        $text .= 'editors.push({';
        $text .= 'name: "' . $this->name . '",';
        $text .= 'stylesheets: ' . json_encode($this->stylesheets) . ',';

        foreach($this->options as $key => $value) {
            $text .= $key . ': "' . $value . '",';
        }

        $text .= '});</script>';

        return $text;
    }

}