<?php

namespace Oxygen\Core\Config;

use Illuminate\Filesystem\Filesystem;

/**
 * Known bugs:
 *
 * Constants & functions
 *   Cause: compileSearchForValue()
 *   Fix: don't use constants or functions
 *
 */

class Rewriter {

    /**
     * Rewrites the value of the $key, in the file $contents.
     * Based upon the assumption that there won't be any duplicate keys and values.
     *
     * @param  string $contents
     * @param  string $key
     * @param  mixed  $oldValue
     * @param         $newValue
     * @throws \Oxygen\Core\Config\RewriteException
     * @return void
     */

    public function rewrite($contents, $key, $oldValue, $newValue) {
        if($key === null || trim($key) === '') {
            return $this->replaceEntireFile($newValue);
        }

        $keyParts = explode('.', $key);
        $lastPartOfKey = last($keyParts);

        $search = $this->compileSearchFor($lastPartOfKey, $oldValue);

        preg_match_all($search, $contents, $matches);

        if(count($matches[0]) < 1) {
            throw new RewriteException('Key not found', $key, $oldValue, $search);
        } else if(count($matches[0]) > 1) {
            throw new RewriteException('Duplicate keys and values found', $key, $oldValue, $search);
        }

        $find = $matches[0][0];
        $replace = $this->compileReplaceFor($lastPartOfKey, $newValue, count($keyParts));

        $newContents = str_replace($find, $replace, $contents);

        return $newContents;
    }

    /**
     * Replaces the entire file.
     *
     * @param string $value
     * @return string
     */

    protected function replaceEntireFile($value) {
        return "<?php\n\n" . $this->compileReplaceForValue($value, 0);
    }

    /**
     * Compiles a regex that will search for the given key and value.
     *
     * @param string $key
     * @param mixed  $value
     * @return string
     */

    protected function compileSearchFor($key, $value) {
        return '/'
            . '["\']' // '
            . $this->quote($key) // key
            . '["\']' // '
            . '[\s]*\=\>[\s]*' // =>
            . $this->compileSearchForValue($value) // 'oldValue'
            . '/';
    }

    /**
     * Compiles a regular expression to match a given value.
     *
     * @param string $value
     * @return string
     */

    protected function compileSearchForValue($value) {
        if(is_string($value)) {
            return $this->compileSearchForString($value);
        } else if(is_array($value)) {
            return $this->compileSearchForArray($value);
        } else if($value === null) {
            return 'null';
        }

        return $this->quote(var_export($value, true));
    }

    /**
     * Compiles a regex that will search for the given string value.
     *
     * @param string $string
     * @return string
     */

    protected function compileSearchForString($string) {
        $slashed = addslashes($string); // places a \ before ' or " or \
        $quoted = '[\'"]' . $this->quote($slashed) . '[\'"]'; // escapes the string and inserts quotes around it
        $optionalSlashes = preg_replace('/\\\\([\'"])/', '[\\\\\\]?$1', $quoted); // matches \' or \" and makes it optional
        $optionalBackslashes = str_replace('\\\\\\\\', '\\\\\\\\?', $quoted); // matches \\
        return $optionalBackslashes;
    }

    /**
     * Compiles a regex that will search for the given array value.
     *
     * @param array $string
     * @return array
     */

    protected function compileSearchForArray($array) {
        $regex = '(array[\s]*\(|\[)[\s]*';
        foreach($array as $key => $item) {
            if(is_int($key)) { $regex .= '('; }
            $regex .= $this->compileSearchForValue($key);
            $regex .= '[\s]*\=\>';
            if(is_int($key)) { $regex .= ')?'; }
            $regex .= '[\s]*';
            $regex .= $this->compileSearchForValue($item);
            $regex .= '[\s]*';
            if($item !== last($array)) {
                $regex .= ',';
            } else {
                $regex .= ',?'; // the last comma is optional
            }
            $regex .= '[\s]*';
        }
        $regex .= '(\)|\])';
        return $regex;
    }

    /**
     * Compiles a replacement string for the given key and value.
     *
     * @param string $key
     * @param mixed $value
     */

    protected function compileReplaceFor($key, $value, $indent) {
        return "'" . $key . "' => " . $this->compileReplaceForValue($value, $indent);
    }

    /**
     * Compiles a replacement string for the given value.
     *
     * @param mixed $value
     */

    protected function compileReplaceForValue($value, $indent) {
        if(is_array($value)) {
            return $this->compileReplaceForArray($value, $indent);
        }

        return var_export($value, true);
    }

    /**
     * Compiles a replacement string for the given array.
     * Acts as a modified var_export()
     *
     * @param array $array
     */

    protected function compileReplaceForArray($array, $indent) {
        $replace = '[';
        foreach($array as $key => $item) {
            $replace .= "\n" . $this->indent($indent + 1);
            if(!is_int($key)) {
                $replace .= $this->compileReplaceForValue($key, $indent + 1);
                $replace .= ' => ';
            }
            $replace .= $this->compileReplaceForValue($item, $indent + 1);
            if($item !== last($array)) {
                $replace .= ',';
            }
        }
        $replace .= "\n" . $this->indent($indent);
        $replace .= ']';
        return $replace;
    }

    /**
     * Runs the string through preg_quote, making
     * it safe to use in regular expressions.
     *
     * @param string $string
     * @return string
     */

    protected function quote($string) {
        return preg_quote($string, '/');
    }

    /**
     * Returns the a number of spaces, specified by the $level multiplied by 4.
     *
     * @param integer $level
     * @return string
     */

    protected function indent($level) {
        $string = '';
        for($i = 0; $i < $level; $i++) {
            $string .= '    ';
        }
        return $string;
    }

}