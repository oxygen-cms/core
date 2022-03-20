<?php

namespace Oxygen\Core\Theme;

interface CurrentThemeLoader {

    /**
     * Returns the current theme.
     *
     * @return string
     */
    public function getCurrentTheme(): string;

    /**
     * Changes the current theme.
     *
     * @param string $theme the name of the new theme
     */
    public function setCurrentTheme(string $theme);

}
