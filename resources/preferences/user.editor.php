<?php

use Illuminate\Contracts\Auth\Guard;
use Oxygen\Auth\Preferences\UserLoader;
use Oxygen\Auth\Repository\UserRepositoryInterface;
use Oxygen\Core\Facades\Preferences;
use Oxygen\Core\Preferences\Schema;

Preferences::register('user.editor', function(Schema $schema) {
    $schema->setTitle('Editor');
    $schema->setLoader(function() {
        return new UserLoader(app(UserRepositoryInterface::class), app(Guard::class), 'editor');
    });

    $schema->makeFields([
        [
            'name'          => 'defaultMode',
            'label'         => 'Default Mode',
            'type'          => 'select',
            'options'       => [
                'code'          => 'Code',
                'split'         => 'Split',
                'preview'       => 'Preview'
            ]
        ],
        [
            'name'          => 'ace.theme',
            'label'         => 'Color Scheme',
            'type'          => 'select',
            'options'       => [
                'Dark' => [
                    'ace/theme/ambiance'                => 'Ambiance',
                    'ace/theme/chaos'                   => 'Chaos',
                    'ace/theme/clouds_midnight'         => 'Clouds Midnight',
                    'ace/theme/cobalt'                  => 'Cobalt',
                    'ace/theme/idle_fingers'            => 'Idle Fingers',
                    'ace/theme/merbivore'               => 'Merbivore',
                    'ace/theme/merbivore_soft'          => 'Merbivore Soft',
                    'ace/theme/mono_industrial'         => 'Mono Industrial',
                    'ace/theme/monokai'                 => 'Monokai',
                    'ace/theme/pastel_on_dark'          => 'Pastel on Dark',
                    'ace/theme/solarized_dark'          => 'Solarized Dark',
                    'ace/theme/terminal'                => 'Terminal',
                    'ace/theme/tomorrow_night'          => 'Tomorrow Night',
                    'ace/theme/tomorrow_night_blue'     => 'Tomorrow Night Blue',
                    'ace/theme/tomorrow_night_bright'   => 'Tomorrow Night Bright',
                    'ace/theme/tomorrow_night_eighties' => 'Tomorrow Night Eighties',
                    'ace/theme/twilight'                => 'Twilight',
                    'ace/theme/vibrant_ink'             => 'Vibrant Ink'
                ],
                'Light' => [
                    'ace/theme/chrome'                  => 'Chrome',
                    'ace/theme/clouds'                  => 'Clouds',
                    'ace/theme/crimson_editor'          => 'Crimson Editor',
                    'ace/theme/dawn'                    => 'Dawn',
                    'ace/theme/dreamweaver'             => 'Dreamweaver',
                    'ace/theme/eclipse'                 => 'Eclipse',
                    'ace/theme/github'                  => 'Github',
                    'ace/theme/kr_theme'                => 'Kr Theme',
                    'ace/theme/solarized_light'         => 'Solarized Light',
                    'ace/theme/textmate'                => 'Textmate',
                    'ace/theme/tomorrow'                => 'Tomorrow',
                    'ace/theme/xcode'                   => 'Xcode'
                ]
            ]
        ],
        [
            'name'          => 'ace.fontSize',
            'label'         => 'Font Size',
            'type'          => 'select',
            'options'       => [
                '10px' => '10',
                '11px' => '11',
                '12px' => '12',
                '13px' => '13',
                '14px' => '14',
                '16px' => '16',
                '18px' => '18',
                '20px' => '20',
                '24px' => '24',
                '32px' => '32'
            ]
        ],
        [
            'name'          => 'ace.wordWrap',
            'label'         => 'Word Wrap',
            'type'          => 'toggle',
            'attributes'    => [
                'labels' => ['on' => 'Wrap', 'off' => 'Don\'t Wrap']
            ]
        ],
        [
            'name'          => 'ace.highlightActiveLine',
            'label'         => 'Highlight Active Line',
            'type'          => 'toggle'
        ],
        [
            'name'          => 'ace.showPrintMargin',
            'label'         => 'Show Print Margins',
            'type'          => 'toggle'
        ],
        [
            'name'          => 'ace.showInvisibles',
            'label'         => 'Show Invisibles',
            'type'          => 'toggle'
        ]
    ]);
});
