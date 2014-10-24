<?php

namespace Oxygen\Core\Factory;

interface FactoryInterface {

    /**
     * Creates a new object using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @return mixed
     */

    public function create(array $parameters);

}