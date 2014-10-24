<?php

namespace Oxygen\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Blueprint extends Facade {

    /**
     * A singular name.
     */

    const SINGULAR = false;

    /**
     * A plural name
     */

    const PLURAL = true;

    protected static function getFacadeAccessor() {
        return 'oxygen.blueprintManager';
    }

}