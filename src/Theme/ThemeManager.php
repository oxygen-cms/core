<?php

namespace Oxygen\Core\Theme;


class ThemeManager {

    /**
     * Themes of the ThemeManager.
     *
     * @var array
     */
    protected $themes;

    /**
     * The theme loader
     *
     * @var CurrentThemeLoader
     */
    protected $loader;

    /**
     * @var string|null
     */
    protected $themeOverride;

    /**
     * Constructs the ThemeManager.
     *
     * @param CurrentThemeLoader $loader
     */
    public function __construct(CurrentThemeLoader $loader) {
        $this->themes = [];
        $this->loader = $loader;
    }

    /**
     * Override the theme for the duration of the request, but do not store it for next time.
     *
     * @param string|null $override
     */
    public function temporarilyOverrideTheme(?string $override) {
        $this->themeOverride = $override;
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
     * @throws ThemeNotFoundException if the theme was not found
     */
    public function get($name) {
        if(isset($this->themes[$name])) {
            return $this->themes[$name];
        } else {
            throw new ThemeNotFoundException('Theme ' . $name . ' not found');
        }
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
     * Returns the theme loader
     *
     * @return CurrentThemeLoader
     */
    public function getLoader() {
        return $this->loader;
    }

    /**
     * Returns the current theme.
     *
     * @return Theme
     * @throws ThemeNotFoundException
     */
    public function current() {
        if($this->themeOverride !== null) {
            return $this->get($this->themeOverride);
        } else {
            return $this->get($this->loader->getCurrentTheme());
        }
    }
}
