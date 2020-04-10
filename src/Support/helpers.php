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
