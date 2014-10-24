<?php

namespace Oxygen\Core\Model\Versionable;

use Oxygen\Core\Model\Model;

class VersionableObserver {

    /**
     * Register the validation event for deleting the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function deleting(Model $model) {
        if($model->isForceDeleting()) {
            if($model->isHead()) {
                $model->versions()->forceDelete();
            }
        }
    }

}