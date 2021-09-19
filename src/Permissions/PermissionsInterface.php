<?php

namespace Oxygen\Core\Permissions;

interface PermissionsInterface {

    /**
     * Check if the user has permissions for the given key.
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool;

}
