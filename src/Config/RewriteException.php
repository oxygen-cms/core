<?php

namespace Oxygen\Core\Config;

use Exception;

class RewriteException extends Exception {

    /**
     * Constructs the RewriteException.
     *
     * @param string $message
     * @param string $key
     * @param string $value
     * @param string $regex
     */

    public function __construct($message, $key, $value, $regex) {
        parent::__construct($message . '. Key: "' . $key . '". Value: "' . print_r($value, true) . '"');
        $this->regex = $regex;
    }

}