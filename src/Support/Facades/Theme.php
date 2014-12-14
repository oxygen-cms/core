<?php

namespace Oxygen\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade {

    protected static function getFacadeAccessor() {
        return 'oxygen.themeManager';
    }

}
