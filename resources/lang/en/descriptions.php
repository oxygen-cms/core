<?php

/*
|--------------------------------------------------------------------------
| Description Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are used in the `description` property of form fields.
|
*/

return [

    'app' => [
        'debug' => 'When your application is in debug mode, detailed error messages with stack traces will be shown on every error that occurs within your application. If disabled, a simple generic error page is shown.',
        'url' => 'This URL is used by the console to properly generate URLs when using the Artisan command line tool. You should set this to the root of your application so that it is used when running Artisan tasks.',
        'timezone' => 'Here you may specify the default timezone for your application, which will be used by the PHP date and date-time functions. We have gone ahead and set this to a sensible default for you out of the box.',
        'locale' => 'The application locale determines the default locale that will be used by the translation service provider. You are free to set this value to any of the locales which will be supported by the application.',
        'fallback_locale' => 'The fallback locale determines the locale to use when the current one is not available. You may change the value to correspond to any of the language folders that are provided through your application.',
        'key' => 'This key is used by the Illuminate encrypter service and should be set to a random, 32 character string, otherwise these encrypted strings will not be safe. Please do this before deploying an application!'
    ],

    'auth' => [
        'driver' => 'This option controls the authentication driver that will be utilized. This drivers manages the retrieval and authentication of the users attempting to get access to protected areas of your application.',
        'model' => 'When using the "Eloquent" authentication driver, we need to know which Eloquent model should be used to retrieve your users.',
        'table' => 'When using the "Database" authentication driver, we need to know which table should be used to retrieve your users. We have chosen a basic default value but you may easily change it to any table you like.',
        'reminder' => [
            'email' => 'The view that should be used when sending password reminder emails.',
            'table' => 'The table that should be used for storing password reminders',
            'expire' => 'The "expire" time is the number of minutes that the reminder should be considered valid. This security feature keeps tokens short-lived so they have less time to be guessed. You may change this as needed.'
        ]
    ],

    'database' => [
        'default' => 'Here you may specify which of the database connections below you wish to use as your default connection for all database work. Of course you may use many connections at once using the Database library.',
        'migrations' => 'This table keeps track of all the migrations that have already run for your application. Using this information, we can determine which of the migrations on disk haven\'t actually been run in the database.'
    ]

];