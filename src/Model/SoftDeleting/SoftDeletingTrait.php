<?php

namespace Oxygen\Core\Model\SoftDeleting;

use Illuminate\Database\Eloquent\SoftDeletingTrait as BaseSoftDeletingTrait;

trait SoftDeletingTrait {

    use BaseSoftDeletingTrait;

    /**
     * Determines whether the model is capable of soft deleting.
     *
     * @return boolean
     */

    public function softDeletes() {
        return true;
    }

    /**
     * Perform the actual delete query on this model instance.
     * Runs the query without scopes.
     *
     * @return void
     */

    protected function performDeleteOnModel() {
        if ($this->forceDeleting) {
            $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey())->forceDelete();
        }

        return $this->runSoftDelete();
    }

    /**
     * Perform the actual delete query on this model instance.
     * Runs the query without scopes.
     *
     * @return void
     */

    protected function runSoftDelete() {
        $query = $this->newQueryWithoutScopes()->where($this->getKeyName(), $this->getKey());

        $this->{$this->getDeletedAtColumn()} = $time = $this->freshTimestamp();

        $query->update(array($this->getDeletedAtColumn() => $this->fromDateTime($time)));
    }

    /**
     * Determines if the model is currently force deleting.
     *
     * @return boolean
     */

    public function isForceDeleting() {
        return $this->forceDeleting;
    }

}
