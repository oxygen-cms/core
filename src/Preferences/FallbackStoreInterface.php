<?php


namespace Oxygen\Core\Preferences;


interface FallbackStoreInterface {

    /**
     * Gets the fallback value for the given key, in any is given. Otherwise throws.
     *
     * @param string $key key using dot notation
     * @return mixed|null the fallback value, or null if not found
     */
    function getFallback(string $key);

    /**
     * Gets the value for the given key, in any is given. Otherwise throws.
     *
     * @param string $key key using dot notation
     * @return mixed|null the fallback value, or null if not found
     */
    function getPrimary(string $key);

}
