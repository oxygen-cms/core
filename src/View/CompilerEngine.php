<?php

namespace Oxygen\Core\View;

use Illuminate\View\Engines\CompilerEngine as BaseCompilerEngine;

class CompilerEngine extends BaseCompilerEngine {

    /**
     * Get the exception message for an exception.
     *
     * @param  \Exception  $e
     * @return string
     */
    protected function getMessage($e) {
        $last = last($this->lastCompiled);
        $path = is_object($last) ? $last->path : realpath($last);
        return $e->getMessage().' (View: '. $path .')';
    }

}