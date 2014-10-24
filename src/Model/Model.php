<?php

namespace Oxygen\Core\Model;

use Oxygen\Core\Model\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent {

    use ValidatingTrait;

    /**
     * Determines whether the model is capable of soft deleting.
     *
     * @return boolean
     */

    public function softDeletes() {
        return false;
    }

}