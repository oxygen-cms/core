<?php

namespace Oxygen\Core\Preferences\Loader;

use Oxygen\Data\Repository\RepositoryInterface;
use Oxygen\Core\Preferences\Loader\Database\PreferenceItem;
use Oxygen\Core\Preferences\PreferencesStorageInterface;

interface PreferenceRepositoryInterface extends RepositoryInterface {

    /**
     * Finds a preference item based upon the key.
     *
     * @param string $key
     * @return PreferencesStorageInterface
     */
    public function findByKey(string $key);

}
