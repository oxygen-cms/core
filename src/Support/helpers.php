<?php

if (!function_exists('html_attributes')) {

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     * @return string
     */
    function html_attributes($attributes) {
        $html = [];
        foreach((array) $attributes as $key => $value) {
            $element = html_attribute_element($key, $value);
            if(!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

}

if (!function_exists('html_attribute_element')) {

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    function html_attribute_element($key, $value) {
        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if(is_numeric($key)) {
            $key = $value;
        }
        if(!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

}
