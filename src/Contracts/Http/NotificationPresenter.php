<?php

namespace Oxygen\Core\Contracts\Http;

use Oxygen\Core\Http\Notification;

interface NotificationPresenter {

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     *
     * Will display a notification to the user and optionally perform another action such as redirecting or refreshing the page
     *
     * $parameters:
     *    redirect (string)     - redirects the user to the given route
     *    refresh (bool)        - refreshes the current page
     *    hardRedirect (bool)   - whether to cause a full page refresh
     *
     * @param Notification $notification   Notification to display.
     * @param array        $parameters     Extra parameters
     * @return mixed
     */
    public function present(Notification $notification, array $parameters = []);

}
