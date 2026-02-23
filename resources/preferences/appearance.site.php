<?php

use Oxygen\Core\Facades\Preferences;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Core\Preferences\Loader\DatabaseLoader;
use Oxygen\Core\Preferences\ThemeSpecificPreferencesFallback;
use Oxygen\Core\Theme\ThemeManager;

Preferences::register('appearance.site', function ($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(
        new DatabaseLoader(app(PreferenceRepositoryInterface::class),
        'appearance.site',
        new ThemeSpecificPreferencesFallback(app(ThemeManager::class), 'appearance.site'))
    );

    $schema->makeField([
        'name' => 'errorViewPrefix',
        'label' => 'Views for HTTP Errors',
        'type' => 'text',
    ]);
});

