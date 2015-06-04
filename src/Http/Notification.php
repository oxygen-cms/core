<?php

namespace Oxygen\Core\Http;

class Notification {

    /**
     * The operation was successful.
     *
     * @var const
     */

    const SUCCESS = 'success';

    /**
     * The user should be warned.
     * The operation may have been partially
     * completed, or perhaps user input was invalid.
     *
     * @var const
     */

    const WARNING = 'warning';

    /**
     * The operation failed completely.
     *
     * @var const
     */

    const FAILED = 'failed';

    /**
     * No particular status, but the
     * given information should be displayed.
     *
     * @var const
     */

    const INFO = 'info';

    /**
     * Status of the notification.
     * Defaults to Notification::SUCCESS.
     *
     * @var string
     */

    protected $status;

    /**
     * Content of the Notification.
     *
     * @var string
     */

    protected $content;

    /**
     * Constructs a new Notification.
     *
     * @param string $content
     * @param string $color
     */
    public function __construct($content, $status = self::SUCCESS) {
        $this->content  = $content;
        $this->status   = $status;
    }

    /**
     * Turns a Notification into an array.
     *
     * @return array
     */
    public function toArray() {
        return array(
            'content'   => $this->content,
            'status'    => $this->status
        );
    }

}
