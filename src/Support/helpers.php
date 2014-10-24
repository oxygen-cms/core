<?php

if (!function_exists('array_diff_recursive')) {

    /**
     * Recursive version of array_diff()
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */

    function array_diff_recursive(array $array1, array $array2) {
        $return = array();

        foreach ($array2 as $key => $value) {
            if(array_key_exists($key, $array1)) {
                if(is_array($value) && is_array($array1[$key])) {
                    $recursiveDiff = array_diff_recursive($value, $array1[$key]);
                    if(count($recursiveDiff)) {
                        $return[$key] = $recursiveDiff;
                    }
                } else {
                    if ($value !== $array1[$key]) {
                        $return[$key] = $value;
                    }
                }
            } else {
              $return[$key] = $value;
            }
        }
        return $return;
    }
}

if (!function_exists('is_assoc')) {

    /**
     * Determine if the array is associative.
     *
     * @param array $array
     * @return boolean
     */

    function is_assoc($array) {
        return (bool) count(array_filter(array_keys($array), 'is_string'));
    }

}



