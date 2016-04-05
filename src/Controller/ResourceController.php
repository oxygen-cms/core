<?php

namespace Oxygen\Core\Controller;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Blueprint\BlueprintManager as BlueprintManager;
use Oxygen\Data\Repository\RepositoryInterface;

class ResourceController extends BlueprintController {

    /**
     * The Repository implementation.
     *
     * @var RepositoryInterface
     */

    protected $repository;

    /**
     * Constructs a ResourceController.
     *
     * @param RepositoryInterface        $repository The Repository implementation
     * @param BlueprintManager|Blueprint $blueprint
     */
    public function __construct(RepositoryInterface $repository, $blueprint) {
        parent::__construct($blueprint);
        $this->repository = $repository;
    }

    /**
     * Checks to see if the passed parameter was an instance
     * of Model, if not it will run a query for the model.
     *
     * @param mixed $item
     * @return object
     */

    protected function getItem($item) {
        if(is_object($item)) {
            return $item;
        } else {
            return $this->repository->find($item);
        }
    }

}
