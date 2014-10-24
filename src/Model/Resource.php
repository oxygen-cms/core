<?php

namespace Oxygen\Core\Model;

use ReflectionClass;

use Blueprint;

abstract class Resource extends Model {

    /**
     * The Oxygen\Core\Blueprint\Blueprint
     * object that this model uses.
     *
     * @var Oxygen\Core\Blueprint
     */

    protected $blueprint;

    /**
     * Constructs a new Resource.
     *
     * @param array $attributes
     * @param string $blueprintName custom name of the blueprint
     * @return void
     */

    public function __construct($attributes = array(), $blueprintName = null) {
        if($blueprintName === null) {
            $class = new ReflectionClass($this);
            $blueprintName = $class->getShortName();
        }

        // get the blueprint
        $this->blueprint = Blueprint::get($blueprintName);

        $this->loadFillableFromBlueprint($this->blueprint);

        parent::__construct($attributes);
    }

    /**
     * Sets several the $fillable attribute
     * based upon the Blueprint's settings.
     *
     * @param Blueprint $blueprint
     * @return void
     */

    protected function loadFillableFromBlueprint($blueprint) {
        $this->fillable = array();
        foreach($blueprint->getFields() as $field) {
            if($field->editable || $field->fillable) {
                $this->fillable[] = $field->name;
            }
        }
    }

    /**
     * Get the global validation rules.
     *
     * @return array
     */

    public function getRules() {
        if(!$this->rules) {
            $this->loadRulesFromBlueprint($this->blueprint);
        }

        return $this->rules;
    }

    /**
     * Loads validation rules into the model when needed.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */

    protected function loadRulesFromBlueprint($blueprint) {
        $this->setRules(
            $this->processRules(
                $this->getBlueprint()->getValidationRules()
            )
        );
    }

    /**
     * Get the model's blueprint.
     *
     * @return Oxygen\Core\Blueprint
     */

    public function getBlueprint() {
        return $this->blueprint;
    }

}