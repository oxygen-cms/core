<?php

/*
|--------------------------------------------------------------------------
| Message Language Lines
|--------------------------------------------------------------------------
|
| The following language lines are returned from API calls and inform the user
| if the action was successful or not.
|
*/

return [

    /*
    | ---------------
    | AJAX Errors
    | ---------------
    |
    | Message to be shown if an error occurs during an AJAX request.
    */

    'ajaxError' => [
        'debug'     => ':exception: :message',
        'default'   => 'Something went wrong'
    ],

    /*
    | ---------------
    | Basic
    | ---------------
    |
    | Language lines for basic resources
    */

    'basic' => [
        'created' => ':resource Created',
        'updated' => ':resource Updated',
        'deleted' => ':resource Deleted',
    ],

    /*
    | ---------------
    | SoftDelete
    | ---------------
    |
    | Language lines for soft deletable resources
    */

    'softDelete' => [
        'restored' => ':resource Restored',
        'forceDeleted' => ':resource Deleted Forever',
    ],

    /*
    | ---------------
    | Versionable
    | ---------------
    |
    | Language lines for versionable resources.
    */

    'versionable' => [
        'madeVersion' => 'A New Version Was Created',
        'clearedVersions' => 'All Old Versions Were Cleared',
        'alreadyHead' => 'That :resource Is Already The Head Version',
        'madeHead' => 'Restored to that version',
    ],

    /*
    | ---------------
    | Publishable
    | ---------------
    |
    | Messages related to publishable entities.
    */
    'publishable' => [
        'published' => ':resource Published',
        'unpublished' => ':resource Unpublished',
        'publishedSoMadeDraft' => 'You are now editing the draft version.',
        'alreadyDraft' => 'The :resource is already a draft.'
    ],

    /*
    | ---------------
    | Upload
    | ---------------
    |
    | Messages related to file uploads.
    */
    'upload' => [
        'noFiles' => 'Please Select a File First',
        'tooLarge' => 'Upload too Large',
        'failed' => 'Failed to upload `:name`: :error',
        'success' => 'File Uploaded Successfully'
    ],

];
