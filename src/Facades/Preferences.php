<?php

namespace Oxygen\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Oxygen\Core\Preferences\PreferencesManager;

class Preferences extends Facade {

    protected static function getFacadeAccessor() {
        return PreferencesManager::class;
    }

}