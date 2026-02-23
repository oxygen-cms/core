<?php namespace Oxygen\Core\Routing;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory as BaseResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Oxygen\Core\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Oxygen\Core\Http\Notification;

class ResponseFactory extends BaseResponseFactory implements ResponseFactoryContract {

    /**
     * Dependencies for the NotificationResponseCreator.
     */
    protected $request, $url, $useSmoothStateCallback;

    /**
     * Injects dependencies for the ResponseFactory.
     *
     * @param ViewFactory $view
     * @param Redirector $redirector
     * @param UrlGenerator $url
     * @param Request $request
     * @param callable $useSmoothStateCallback
     */
    public function __construct(ViewFactory $view, Redirector $redirector, UrlGenerator $url, Request $request, callable $useSmoothStateCallback) {
        parent::__construct($view, $redirector);
        $this->request = $request;
        $this->url = $url;
        $this->useSmoothStateCallback = $useSmoothStateCallback;
    }

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     *
     * The $redirect parameter will redirect the user to the given route name.
     * The
     *
     * @param Notification $notification   Notification to display.
     * @param array        $parameters     Extra parameters
     * @return SymfonyResponse
     */
    public function notification(Notification $notification, array $parameters = []) {
        $notification = $this->arrayFromNotification($notification);

        $url = null;
        if($this->wantsRedirect($parameters)) {
            $url = $this->urlFromRoute($parameters['redirect']);
        } else if($this->wantsRefresh($parameters)) {
            $url = $this->redirector->getUrlGenerator()->previous();
        }

        if($this->request->wantsJson()) {
            return $this->createJsonResponse($notification, $url, $parameters);
        } else {
            return $this->createBasicResponse($notification, $url, $parameters);
        }
    }

    /**
     * Returns a json response with a reload command.
     * If $refresh is true then the user will be sent to the previous page.
     * If $refresh is false then the user will be sent to the specified page.
     *
     * @param mixed $notification Notification to display.
     * @param null|string $url         The URL to redirect to or null to just display the notification
     * @param array $parameters   Extra parameters
     * @return SymfonyResponse
     */
    protected function createJsonResponse($notification, ?string $url, array $parameters) {
        if($url === null) {
            return $this->makeCustomResponse(new JsonResponse($notification), $parameters);
        }

        $return = [
            'redirect' => $url
        ];

        if(isset($parameters['hardRedirect'])) {
            $return['hardRedirect'] = $parameters['hardRedirect'];
        }

        // display the message on the new page
        $callback = $this->useSmoothStateCallback;
        if($callback() && !isset($return['hardRedirect'])) {
            $return = array_merge($return, $notification);
        } else {
            $this->request->session()->flash('adminMessage', $notification);
        }

        // send the redirect command
        return $this->makeCustomResponse(new JsonResponse($return), $parameters);
    }

    /**
     * Returns a basic response.
     *
     * @param mixed  $notification Flash message to display.
     * @param string $url          The URL to redirect to or null to stay on the current page.
     * @param array  $parameters
     * @return SymfonyResponse
     */
    public function createBasicResponse($notification, $url, $parameters) {
        if($url == null) {
            $url = $this->url->previous();
        }

        // flash data to the session
        $this->request->session()->flash('adminMessage', $notification);

        return $this->makeCustomResponse($this->redirector->to($url), $parameters);
    }

    /**
     * Decode the route argument into a URL.
     *
     * @param mixed $route
     * @return string
     */
    protected function urlFromRoute($route) {
        if(is_array($route)) { // ['routeName', ['param1', 3]]
            return $this->url->route($route[0], $route[1]);
        } else if(parse_url($route, PHP_URL_SCHEME) != '')  { // determine if absolute URL already
            return $route;
        } else  { // 'routeName'
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
     * @param SymfonyResponse $response
     * @param array $parameters
     * @return RedirectResponse|Response|SymfonyResponse
     */
    protected function makeCustomResponse(SymfonyResponse $response, $parameters) {
        if(isset($parameters['input']) && $parameters['input'] === true && $response instanceof RedirectResponse) {
            return $response->withInput();
        } else if(isset($parameters['customResponse'])) {
            return $parameters['customResponse']();
        } else {
            return $response;
        }
    }

}
