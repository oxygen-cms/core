<?php

namespace Oxygen\Core\Support\Facades;

use Illuminate\Support\Facades\Response as BaseResponse;
use Illuminate\Support\Facades\Facade;
use Oxygen\Core\Contracts\Http\NotificationPresenter;

class Response extends BaseResponse {

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     * Uses the NotificationResponseCreator class behind the scenes.
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters.
     * @return mixed
     */
    public static function notification($notification, array $parameters = []) {
        return Facade::getFacadeApplication()[NotificationPresenter::class]
            ->present($notification, $parameters);
    }

}
