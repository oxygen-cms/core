<?php


namespace Oxygen\Core\Preferences;


interface PreferencesStoreInterface {

    /**
     * Get the specified preferences value. Throws if not found.
     *
     * @param string $key key using dot notation
     * @return mixed
     */
    public function get(string $key);

    /**
     * Gets the specified preferences value, or returns $default if not found.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOrDefault(string $key, $default);

    /**
     * Sets the specified preferences item.
     * Will not persist changes automatically.
     *
     * @param string $key key using dot notation
     * @param mixed $value new value
     * @return void
     */
    public function set(string $key, $value);

    /**
     * Checks if the preferences item has been set.
     *
     * @param string $key key using dot notation
     * @return boolean
     */
    public function has(string $key): bool;
}
