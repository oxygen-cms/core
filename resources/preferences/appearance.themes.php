<?php

use Illuminate\Validation\Rule;
use Oxygen\Core\Facades\Preferences;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;
use Oxygen\Core\Preferences\Loader\DatabaseLoader;
use Oxygen\Core\Theme\Theme;
use Oxygen\Core\Theme\ThemeManager;

Preferences::register('appearance.themes', function($schema) {
    $schema->setTitle('Themes');
    $schema->setLoader(new DatabaseLoader(app(PreferenceRepositoryInterface::class), 'appearance.themes'));

    $themes = app(ThemeManager::class)->all();

    $schema->makeField([
        'name' => 'theme',
        'label' => 'Theme',
        'type' => 'select',
        'options' => function() use($themes) {
            return array_map(function(Theme $theme) {
                return $theme->toArray();
            }, $themes);
        },
        'validationRules' => [
            Rule::in(array_map(function(Theme $theme) {
                return $theme->getKey();
            }, $themes))
        ]
    ]);
});
