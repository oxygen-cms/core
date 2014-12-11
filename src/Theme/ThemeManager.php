<?php

namespace Oxygen\Core\Theme;

use Illuminate\Config\Repository;

class ThemeManager {

    /**
     * Themes of the ThemeManager.
     *
     * @var array
     */

    protected $themes;

    /**
     * Constructs the ThemeManager.
     */

    public function __construct(Repository $config) {
        $this->themes = [];
        $this->config = $config;
    }

    /**
     * Registers a theme.
     *
     * @param Theme $theme
     */

    public function register(Theme $theme) {
        $this->themes[$theme->getKey()] = $theme;
    }

    /**
     * Makes a theme.
     *
     * @param array $arguments
     */

    public function make(array $arguments) {
        $theme = new Theme($arguments['key']);
        unset($arguments['key']);
        $theme->fillFromArray($arguments);
        $this->register($theme);
    }

    /**
     * Returns a theme by name.
     *
     * @param string $name
     * @return Theme
     */

    public function get($name) {
        if($name === null) {
            return null;
        }
        return $this->themes[$name];
    }


    /**
     * Returns all themes.
     *
     * @return array
     */

    public function all() {
        return $this->themes;
    }

    /**
     * Returns the current key of the theme.
     *
     * @return string
     */

    public function getCurrentKey() {
        return $this->config->get('oxygen/core::theme');
    }

    /**
     * Sets the current theme
     *
     * @param string $key
     * @return void
     */

    public function setCurrentKey($key) {
        $this->config->write('oxygen/core::theme', $key);
    }

    /**
     * Returns the current theme.
     *
     * @return Theme
     */

    public function current() {
        return $this->get($this->getCurrentKey());
    }
}
