<?php

namespace Oxygen\Core\Entity\Repository\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Oxygen\Core\Entity\Repository\RepositoryInterface;

class Repository implements RepositoryInterface {

    /**
     * The model instance
     *
     * @var Model
     */

    protected $model;

    /**
     * Retrieves all identities.
     *
     * @param array|string $scopes an optional array of query scopes
     * @return mixed
     */

    public function all($scopes = []) {
        return $this->model->newQuery()->get();
    }

    /**
     * Retrieves a single entity.
     *
     * @param integer      $id
     * @param array|string $scopes an optional array of query scopes
     * @return mixed
     */

    public function find($id, $scopes = []) {
        return $this->model->newQuery()->findOrFail($id);
    }

    /**
     * Creates a new entity
     *
     * @return object
     */
    public function make() {
        return $this->model->newInstance();
    }

    /**
     * Persists an entity.
     *
     * @param object $entity
     * @return void
     */

    public function persist($entity) {
        $entity->save();
    }

    /**
     * Deletes an entity.
     *
     * @param object $entity
     * @return void
     */

    public function delete($entity) {
        $entity->delete();
    }

}
