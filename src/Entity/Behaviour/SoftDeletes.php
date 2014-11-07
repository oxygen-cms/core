<?php

namespace Oxygen\Core\Entity\Behaviour;

use DateTime;
use Mitch\LaravelDoctrine\Traits\SoftDeletes as BaseSoftDeletes;

trait SoftDeletes {

    use BaseSoftDeletes;

    /**
     * Soft-deletes the model.
     *
     * @return void
     */

    public function delete() {
        $this->setDeletedAt(new DateTime());
    }

    /**
     * Restores the model.
     *
     * @return void
     */

    public function restore() {
        $this->setDeletedAt(null);
    }

}

