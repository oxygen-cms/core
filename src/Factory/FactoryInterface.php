<?php

namespace Oxygen\Core\Factory;

interface FactoryInterface {

    /**
     * Creates a new object using the passed parameters.
     *
     * @param array $parameters Passed parameters
     * @param string|null $controller Default controller to use
     * @return mixed
     */
    public function create(array $parameters, string $controller = null);

}
