<?php

namespace Oxygen\Core\Html\Dialog;

use Oxygen\Core\Html\RenderableTrait;

class Dialog {

    use RenderableTrait;

    /**
     * When clicked a confirmation box will pop-up,
     * asking the user to confirm their action.
     * If they cancel then the link will not fire.
     *
     * @var string
     */

    const TYPE_CONFIRM = 'confirm';

    /**
     * Message to be displayed in the Dialog.
     *
     * @var string
     */

    public $message;

    /**
     * Type of the Dialog.
     *
     * @var string
     */

    public $type;

    public function __construct($message, $type = self::TYPE_CONFIRM) {
        $this->message = $message;
        $this->type = $type;
    }

}