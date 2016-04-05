<?php

namespace Oxygen\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Oxygen\Core\Html\Navigation\Navigation as NavigationManager;

class Navigation extends Facade {

    /**
     * Primary toolbar.
     *
     * @var integer
     */

    const PRIMARY = 'primary';

    /**
     * Secondary toolbar.
     *
     * @var integer
     */

    const SECONDARY = 'secondary';

    protected static function getFacadeAccessor() {
        return NavigationManager::class;
    }

}
