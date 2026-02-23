<?php


namespace Oxygen\Core\Preferences;


interface PreferencesSettingInterface {

    /**
     * Sets the preferences value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value);

}
