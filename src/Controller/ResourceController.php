<?php

namespace Oxygen\Core\Controller;

use Oxygen\Data\Repository\RepositoryInterface;
use Oxygen\Core\Blueprint\Manager as BlueprintManager;

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
     * @param RepositoryInterface $repository    The Repository implementation
     * @param BlueprintManager    $manager       BlueprintManager instance
     * @param string              $blueprintName Name of the corresponding Blueprint
     */
    public function __construct(RepositoryInterface $repository, BlueprintManager $manager, $blueprintName = null) {
        parent::__construct($manager, $blueprintName);
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
