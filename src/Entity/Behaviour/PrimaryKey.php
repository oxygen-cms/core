<?php

namespace Oxygen\Core\Entity\Behaviour;

use BadMethodCallException;
use InvalidArgumentException;

trait PrimaryKey {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id;

}

