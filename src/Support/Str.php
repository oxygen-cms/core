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
        return preg_replace('/(?<=[A-Z])(?=[A-Z][a-z])|(?<=[^A-Z])(?=[A-Z])|(?<=[A-Za-z])(?=[^A-Za-z])/', ' ', $value);
    }

}
