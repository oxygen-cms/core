<?php

namespace Oxygen\Core\Contracts\Config;

use Illuminate\Contracts\Config\Repository;

interface WritableRepository extends Repository {

    /**
     * Writes the configuration key and value to a file.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function write($key, $value);

}
