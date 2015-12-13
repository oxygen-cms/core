<?php

namespace Oxygen\Core\Routing;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory as BaseResponseFactory;
use Oxygen\Core\Contracts\Http\NotificationPresenter;
use Oxygen\Core\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class ResponseFactory extends BaseResponseFactory implements ResponseFactoryContract {

    /**
     * The notification presenter.
     *
     * @var \Oxygen\Core\Contracts\Http\NotificationPresenter
     */
    protected $notificationPresenter;

    private $notificationPresenterFactory;

    /**
     * Create a new response factory instance.
     *
     * @param  \Illuminate\Contracts\View\Factory     $view
     * @param  \Illuminate\Routing\Redirector         $redirector
     * @param callable                                $notificationFactory
     */
    public function __construct(ViewFactory $view, Redirector $redirector, callable $notificationFactory) {
        parent::__construct($view, $redirector);
        $this->notificationPresenterFactory = $notificationFactory;
    }

    /**
     * Creates a response to an API call that handles AJAX requests as well.
     * Uses the NotificationPresenter class behind the scenes.
     *
     * @param mixed     $notification   Notification to display.
     * @param array     $parameters     Extra parameters.
     * @return \Illuminate\Http\Response
     */
    public function notification($notification, array $parameters = []) {
        return $this->getNotificationPresenter()->present($notification, $parameters);
    }

    private function getNotificationPresenter() {
        if($this->notificationPresenter == null) {
            $callable = $this->notificationPresenterFactory;
            $this->notificationPresenter = $callable();
        }
        return $this->notificationPresenter;
    }

}
