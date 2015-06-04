<?php

namespace Oxygen\Core\Blueprint;

interface BlueprintTraitInterface {

    /**
     * Apply the trait to the blueprint.
     *
     * @param Blueprint $blueprint
     */
    public function applyTrait(Blueprint $blueprint);

}
