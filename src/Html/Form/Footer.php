<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Html\RenderableTrait;

class Footer {

    use RenderableTrait;

    /**
     * Array containing the contents of the Footer.
     *
     * @var array
     */

    public $parameters;

    /**
     * Constructs the object.
     *
     * @param array $parameters
     */

    public function __construct(array $parameters) {
        foreach($parameters as &$button) {
            if(!isset($button['type'])) {
                $button['type'] = 'link';
            }
        }

        $this->parameters = $parameters;
    }

}