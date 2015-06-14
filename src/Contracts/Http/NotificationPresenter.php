<?php

namespace Oxygen\Core\Contracts\Http;

use Oxygen\Core\Http\Notification;

interface NotificationPresenter {

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     *
     * The $redirect parameter will redirect the user to the given route name.
     * The
     *
     * @param Notification $notification   Notification to display.
     * @param array        $parameters     Extra parameters
     * @return mixed
     */
    public function present(Notification $notification, array $parameters = []);

}
