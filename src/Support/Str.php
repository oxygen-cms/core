<?php

namespace Oxygen\Core\Support;

use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr {

    /**
     * Converts a case-delimited string (eg camel case) into words
     *
     * @param  string  $value
     * @return string
     */

    public static function camelToWords($value) {
        return preg_replace_callback('/(?<!\b)[A-Z][a-z]+|(?<=[a-z])[A-Z]/', function($match) {
            return ' '. $match[0];
        }, $value);
    }

}