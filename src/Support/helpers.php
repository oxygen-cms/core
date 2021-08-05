<?php

use Illuminate\Http\Response;
use Oxygen\Core\Contracts\Routing\ResponseFactory;
use Oxygen\Core\Http\Notification;

if(!function_exists('html_attributes')) {

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array $attributes
     * @return string
     */
    function html_attributes($attributes) {
        $html = [];
        foreach((array)$attributes as $key => $value) {
            $element = html_attribute_element($key, $value);
            if(!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

}

if(!function_exists('html_attribute_element')) {

    /**
     * Build a single attribute element.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    function html_attribute_element($key, $value) {
        // Boolean options will either print `keyNameHere` or ``
        if($value === true) {
            return $key;
        } else {
            if($value === false) {
                return '';
            }
        }

        if(!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }

}

if(!function_exists('notify')) {
    /**
     * Creates a response to an API call that handles AJAX requests as well.
     *
     * Will display a notification to the user and optionally perform another action such as redirecting or refreshing the page
     *
     * $parameters:
     *    redirect (string)     - redirects the user to the given route
     *    refresh (bool)        - refreshes the current page
     *    hardRedirect (bool)   - whether to cause a full page refresh
     *
     * @param Notification $notification Notification to display.
     * @param array        $parameters   Extra parameters
     * @return Response
     */
    function notify(Notification $notification, array $parameters = []) {
        return app(ResponseFactory::class)->notification($notification, $parameters);
    }
}

if(!function_exists('array_merge_recursive_distinct')) {
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * @param array $arrays
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @author Chris Chamberlain
     */
    function array_merge_recursive_distinct(array... $arrays): array {
        $merged = array_shift($arrays);

        foreach($arrays as $nextArrayToMerge) {
            foreach ($nextArrayToMerge as $key => &$value) {
                if(is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
                } else if($value) {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}

if(!function_exists('array_merge_recursive_ignore_null')) {
    function array_merge_recursive_ignore_null(array... $arrays): array {
        $merged = array_shift($arrays);

        foreach($arrays as $nextArrayToMerge) {
            foreach ($nextArrayToMerge as $key => &$value) {
                if(is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = array_merge_recursive_ignore_null($merged[$key], $value);
                } else if($value !== null) {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }
}
