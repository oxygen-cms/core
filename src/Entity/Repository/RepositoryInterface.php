<?php

namespace Oxygen\Core\Entity\Repository;

use Oxygen\Core\Entity\Exception\NoResultException;

interface RepositoryInterface {

    /**
     * Retrieves all entities.
     *
     * @param array|string  $scopes an optional array of query scopes
     * @return mixed
     */

    public function all($scopes = []);

    /**
     * Retrieves a single entity.
     *
     * @param integer       $id
     * @param array|string  $scopes an optional array of query scopes
     * @return object
     * @throws NoResultException if no result was found
     */

    public function find($id, $scopes = []);

    /**
     * Creates a new entity
     *
     * @return object
     */

    public function make();

    /**
     * Persists an entity.
     *
     * @param object $entity
     * @return void
     */

    public function persist($entity);

    /**
     * Deletes an entity.
     *
     * @param object $entity
     * @return void
     */

    public function delete($entity);

}
