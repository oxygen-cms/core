<?php

namespace Oxygen\Core\Form;

class Form {

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'POST';
    const PATCH = 'POST';
    const DELETE = 'POST';

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

    public function __construct($method = self::POST) {
        $this->method = $method;
    }

}
