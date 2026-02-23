<?php

namespace Oxygen\Core\Preferences;

use Illuminate\Foundation\Events\Dispatchable;

class SchemaRegistered {

    use Dispatchable;

    private string $key;

    /**
     * A schema with key $key has been registered.
     *
     * @param string $key
     */
    public function __construct(string $key) {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

}