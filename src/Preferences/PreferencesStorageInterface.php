<?php


namespace Oxygen\Core\Preferences;

interface PreferencesStorageInterface {

    /**
     * Returns an array of preferences.
     *
     * @return array
     */
    public function getPreferences();

    /**
     * Sets the preferences repository.
     *
     * @param array $preferences
     * @return void
     */
    public function setPreferences(array $preferences);

}
