<?php


namespace Oxygen\Core\Contracts\Routing;

use Illuminate\Contracts\Routing\ResponseFactory as BaseResponseFactory;

interface ResponseFactory extends BaseResponseFactory {

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     * Uses the NotificationPresenter class behind the scenes.
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters.
     * @return \Illuminate\Http\Response
     */
    public function notification($notification, array $parameters = []);
}
