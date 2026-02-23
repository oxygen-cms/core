<?php

namespace Oxygen\Core\Preferences\Loader;

use Oxygen\Core\Preferences\PreferencesStoreInterface;

interface LoaderInterface {

    /**
     * Loads the preferences and returns the repository.
     *
     * @return PreferencesStoreInterface
     */
    public function load(): PreferencesStoreInterface;

    /**
     * Stores the preferences.
     *
     * @return void
     */
    public function store();

}
