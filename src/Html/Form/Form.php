<?php

namespace Oxygen\Core\Form;

use Oxygen\Core\Http\Method;

class Form {

    /**
     * An array of form rows
     *
     * @var array
     */
    protected $rows;

    /**
     * The method for the form.
     *
     * @var string
     */
    protected $method;

    public function __construct($method = Method::POST) {
        $this->method = $method;
    }

}
