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
        // Boolean options will either print `keyNameHere` or ``
        if($value === true) {
            return $key;
        } else if($value === false) {
            return '';
        }

        if(!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

}
