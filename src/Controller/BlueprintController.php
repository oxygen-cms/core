<?php

namespace Oxygen\Core\Controller;

use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Blueprint\BlueprintManager as BlueprintManager;
use ReflectionClass;
use Oxygen\Core\Support\Str;

class BlueprintController extends Controller {

    /**
     * Blueprint for the Resource.
     *
     * @var \Oxygen\Core\Blueprint\Blueprint
     */

    protected $blueprint;

    /**
     * Constructs a BlueprintController.
     *
     * @param BlueprintManager | string $blueprint The blueprint or blueprint manager
     * @throws \ReflectionException
     * @throws \Oxygen\Core\Blueprint\BlueprintNotFoundException
     */
    public function __construct($blueprint) {
        if($blueprint instanceof BlueprintManager) {
            $reflect = new ReflectionClass($this);
            $blueprintName = Str::singular(str_replace('Controller', '', $reflect->getShortName()));
            $this->blueprint = $blueprint->get($blueprintName);
        } else {
            if($blueprint instanceof Blueprint) {
                $this->blueprint = $blueprint;
            } else {
                throw new \InvalidArgumentException('$blueprint should be an instance of \Oxygen\Core\Blueprint\Blueprint or \Oxygen\Core\Blueprint\BlueprintManager');
            }
        }

        view()->share('blueprint', $this->blueprint);
    }

}
