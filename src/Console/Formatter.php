<?php

namespace Oxygen\Core\Console;

class Formatter {

    /**
     * Formats a boolean value for console output.
     *
     * @param array $array
     * @return string
     */
    public static function boolean($boolean) {
        return ($boolean) ? 'Yes' : 'No';
    }

    /**
     * Displays the number of items in an array.
     *
     * @param array $array
     * @return string
     */
    public static function count($array) {
        return count($array) . ' items';
    }

    /**
     * Formats an array for console output.
     *
     * @param array $array
     * @return string
     */
    public static function shortArray($array) {
        if(!is_array($array)) {
            return $array;
        } else {
            return '[ ' . implode(', ', $array) . ' ]';
        }
    }

    /**
     * Formats an array for console output.
     *
     * @param array $array
     * @return string
     */
    public static function associativeArray(array $array) {
        $string = '[ ';
        foreach($array as $key => $item) {
            $string .= $key . ' => ' . $item;
            if($item !== last($array)) {
                $string .= ', ';
            }
        }
        $string .= ' ]';
        return $string;
    }

    /**
     * Formats an array for console output.
     *
     * @param array $array
     * @return string
     */
    public static function keyedArray($array) {
        if(is_array($array)) {
            $string = '[ ';
            foreach($array as $key => $item) {
                if(!is_string($item)) {
                    $item = self::keyedArray($item);
                }
                $string .= $key . ' => ' . $item;
                if($item !== last($array)) {
                    $string .= ', ';
                }
            }
            $string .= ' ]';
            return $string;
        } else if(is_object($array)) {
            return get_class($array);
        } else if(is_null($array)) {
            return 'Null';
        } else {
            return 'Unknown';
        }
    }

    /**
     * Formats an object for console output.
     *
     * @param object $object
     * @return string
     */
    public static function object($object) {
        if($object !== null) {
            return static::keyedArray((array) $object);
        } else {
            return 'None';
        }
    }

}
