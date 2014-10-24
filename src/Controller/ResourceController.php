<?php

namespace Oxygen\Core\Controller;

use ReflectionClass;

use Oxygen\Core\Model\Model;
use Oxygen\Core\Blueprint\Manager as BlueprintManager;

class ResourceController extends BlueprintController {

    /**
     * Constructs a ResourceController.
     *
     * @param BlueprintManager $manager       BlueprintManager instance
     * @param string           $blueprintName Name of the corresponding Blueprint
     * @param string           $modelName     Name of the corresponding model
     */

    public function __construct(BlueprintManager $manager, $blueprintName = null, $modelName = null) {
        parent::__construct($manager, $blueprintName);

        if($modelName === null) {
            $reflect = new ReflectionClass($this);
            $modelName = str_singular(str_replace('Controller', '', $reflect->getShortName()));
        }

        $this->model = new $modelName;
    }

    /**
     * Checks to see if the passed parameter was an instance
     * of Model, if not it will run a query for the model.
     *
     * @param mixed $item
     * @return Model
     */

    protected function getItem($item) {
        if($item instanceof Model) {
            return $item;
        } else {
            return $this->queryAll()->findOrFail($item);
        }
    }

    /**
     * Returns a QueryBuilder that will
     * include all special models such
     * as soft-deleted models & non-head-versions.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */

    protected function queryAll() {
        return $this->model->newQuery();
    }

}
