<?php

namespace Oxygen\Core\Http;

use Illuminate\Session\Store as Session;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Routing\UrlGenerator as URL;

use Oxygen\Preferences\Repository;

class NotificationResponseCreator {

    /**
     * Dependencies for the NotificationResponseCreator.
     */

    protected $session, $request, $response, $redirect, $url, $preferences;

    /**
     * Injects dependencies for the NotificationResponseCreator.
     *
     * @param Session $session
     * @param Request $request
     * @param Response $response
     * @param Redirect $redirect
     * @param URL $url
     * @param Repository $preferences
     */
    public function __construct(Session $session, Request $request, Response $response, Redirect $redirect, URL $url, Repository $preferences = null) {
        $this->session = $session;
        $this->request = $request;
        $this->response = $response;
        $this->redirect = $redirect;
        $this->url = $url;
        $this->preferences = $preferences;
    }

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     *
     * The $redirect parameter will redirect the user to the given route name.
     * The
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters
     * @return mixed
     */
    public function createResponse($notification, array $parameters = []) {
        $notification = $this->arrayFromNotification($notification);

        if($this->request->ajax()) {
            if($this->wantsRedirect($parameters)) {
                return $this->createJsonRedirectResponse($notification, $parameters);
            } else if($this->wantsRefresh($parameters)) {
                return $this->createJsonRedirectResponse($notification, $parameters, true);
            } else {
                return $this->createJsonSmoothResponse($notification, $parameters);
            }
        } else {
            return $this->createBasicResponse($notification, $parameters);
        }
    }

    /**
     * Returns a json response with a reload command.
     * If $refresh is true then the user will be sent to the previous page.
     * If $refresh is false then the user will be sent to the specified page.
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters
     * @param boolean   $refresh        Whether to refresh
     * @return Response
     */

    protected function createJsonRedirectResponse($notification, $parameters, $refresh = false) {
        if($refresh) {
            $url = $this->url->previous();
        } else {
            $url = $this->urlFromRoute($parameters['redirect']);
        }

        $return = [
            'redirect' => $url
        ];

        if(isset($parameters['hardRedirect'])) {
            $return['hardRedirect'] = $parameters['hardRedirect'];
            $hardRedirect = true;
        }

        // display the message on the new page
        if(($this->preferences === null || $this->preferences->get('smoothState.enabled', true) === true) && !isset($hardRedirect)) {
            $return = array_merge($return, $notification);
        } else {
            $this->session->flash('adminMessage', $notification);
        }

        // send the redirect command
        return $this->makeCustomResponse($this->response->json($return), $parameters);
    }

    /**
     * Returns a JSON response.
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters
     * @return Response
     */

    private function createJsonSmoothResponse($notification, $parameters) {
        return $this->makeCustomResponse($this->response->json($notification), $parameters);
    }

    /**
     * Returns a basic response.
     *
     * @param mixed     $notification   Flash message to display.
     * @param array     $parameters
     * @return
     */
    public function createBasicResponse($notification, $parameters) {
        if($this->wantsRedirect($parameters)) {
            $url = $this->urlFromRoute($parameters['redirect']);
        } else if(isset($parameters['fallback'])) {
            $url = $this->urlFromRoute($parameters['fallback']);
        } else {
            $url = $this->url->previous();
        }

        // flash data to the session
        $this->session->flash('adminMessage', $notification);

        return $this->makeCustomResponse($this->redirect->to($url), $parameters);
    }

    /**
     * Decode the route argument into a URL.
     *
     * @param mixed $route
     * @return array
     */

    protected function urlFromRoute($route) {
        if(is_array($route)) {
            return $this->url->route($route[0], $route[1]);
        } else {
            return $this->url->route($route);
        }
    }

    /**
     * Get the raw array from the notification.
     *
     * @param mixed $notification
     * @return array
     */

    protected function arrayFromNotification($notification) {
        if(!is_array($notification)) {
            return $notification->toArray();
        } else {
            return $notification;
        }
    }

    /**
     * Determine if the user should be redirected.
     *
     * @param array $parameters
     * @return boolean
     */

    protected function wantsRedirect($parameters) {
        return isset($parameters['redirect']);
    }

    /**
     * Determine if the page should be refreshed.
     *
     * @param array $parameters
     * @return boolean
     */

    protected function wantsRefresh($parameters) {
        return isset($parameters['refresh']) && $parameters['refresh'] === true;
    }

    /**
     * Runs the response through the custom response callback, if it exists.
     *
     * @param Response $response
     * @param array $parameters
     * @return Response
     */

    protected function makeCustomResponse($response, $parameters) {
        if(isset($parameters['input']) && $parameters['input'] === true && !($response instanceof JsonResponse)) {
            return $response->withInput();
        } else if(isset($parameters['customResponse'])) {
            return $parameters['customResponse']();
        } else {
            return $response;
        }
    }

}
