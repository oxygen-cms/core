<?php

namespace Oxygen\Core\Controller;

use View;

use ReflectionClass;

use Oxygen\Core\Blueprint\Manager as BlueprintManager;

class BlueprintController extends Controller {

    /**
     * Blueprint for the Resource.
     *
     * @var Oxygen\Core\Blueprint\Blueprint
     */

    protected $blueprint;

    /**
     * Constructs a BlueprintController.
     *
     * @param BlueprintManager  $manager        BlueprintManager instance
     * @param string            $blueprintName  Name of the corresponding Blueprint
     */
    public function __construct(BlueprintManager $manager, $blueprintName = null) {
        if($blueprintName === null) {
            $reflect = new ReflectionClass($this);
            $blueprintName = str_singular(str_replace('Controller', '', $reflect->getShortName()));
        }

        // get the blueprint and api objects
        $this->blueprint = $manager->get($blueprintName);

        View::composer('*', function($view) {
            if(!isset($view['blueprint'])) {
                $view->with('blueprint', $this->blueprint);
            }
        });
    }

}
