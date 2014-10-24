<?php

namespace Oxygen\Core\Model\Validating;

use Exception;

use Oxygen\Core\Model\Model;

class InvalidModelException extends Exception {

    /**
     * The model that is invalid.
     *
     * @var Model
     */

    protected $model;

    /**
     * Constructs the InvalidModelException.
     *
     * @param Model $model the invalid model
     */

    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * Returns the invalid model.
     *
     * @return Model
     */

    public function getModel() {
        return $this->model;
    }

    /**
     * Returns the invalid model.
     *
     * @return Model
     */

    public function getErrors() {
        return $this->model->getErrors();
    }

}