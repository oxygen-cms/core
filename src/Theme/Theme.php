<?php

namespace Oxygen\Core\Theme;

class Theme {

    /**
     * Key of the Theme
     *
     * @var string
     */

    protected $key;

    /**
     * Name of the Theme
     *
     * @var string
     */

    protected $name;

    /**
     * Config items that the theme 'provides'
     *
     * @var array
     */

    protected $provides;

    /**
     * Image of the Theme
     *
     * @var string
     */

    protected $image;

    /**
     * Booting callback
     *
     * @var callable
     */

    protected $bootCallback;

    /**
     * Constructs the Theme.
     *
     * @param $key
     */
    public function __construct($key) {
        $this->key = $key;
        $this->provides = [];
        $this->bootCallback = function() {};
    }

    /**
     * Returns the key of the Theme.
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Sets the name of the Theme.
     *
     * @param string $name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Returns the name of the Theme.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Adds an item to the 'provides' array.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function provides($name, $value) {
        $this->provides[$name] = $value;
    }

    /**
     * Returns the entire $provides array.
     *
     * @return array
     */
    public function getProvides() {
        return $this->provides;
    }

    /**
     * Returns the image.
     *
     * @param string $image
     * @return void
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * Determines the image has been set.
     *
     * @return boolean
     */
    public function hasImage() {
        return $this->image !== null;
    }

    /**
     * Returns the image.
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Sets the boot callback.
     *
     * @param $callback
     */
    public function setBootCallback($callback) {
        $this->bootCallback = $callback;
    }

    /**
     * Boots the theme.
     *
     * @return void
     */
    public function boot() {
        $callback = $this->bootCallback;
        $callback();
    }

    /**
     * Fills the theme info from an array.
     *
     * @param array $arguments
     * @return void
     */
    public function fillFromArray(array $arguments) {
        if(isset($arguments['name'])) {
            $this->name = $arguments['name'];
        }

        if(isset($arguments['image'])) {
            $this->image = $arguments['image'];
        }

        if(isset($arguments['provides'])) {
            $this->provides = $arguments['provides'];
        }

        if(isset($arguments['boot'])) {
            $this->bootCallback = $arguments['boot'];
        }
    }
}
