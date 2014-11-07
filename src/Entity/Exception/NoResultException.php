<?php

namespace Oxygen\Core\Entity\Exception;

use Exception;
use RuntimeException;

class NoResultException extends RuntimeException {

    /**
     * Constructor.
     *
     * @param Exception $previous
     */

    public function __construct(Exception $previous = null) {
        parent::__construct('No result was found for query', 0, $previous);
    }

}
