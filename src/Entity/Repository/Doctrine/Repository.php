<?php

namespace Oxygen\Core\Entity\Repository\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\NoResultException as DoctrineNoResultException;
use InvalidArgumentException;
use Oxygen\Core\Entity\Exception\NoResultException;
use Oxygen\Core\Entity\Repository\RepositoryInterface;

class Repository implements RepositoryInterface {

    /**
     * The entity manager
     *
     * @var EntityManagerInterface
     */

    protected $entities;

    /**
     * The name of the entity.
     *
     * @var string
     */

    protected $entityName;

    /**
     * Constructs the DoctrineRepository.
     *
     * @param EntityManagerInterface $entities
     * @param string $entityName
     */

    public function __construct(EntityManagerInterface $entities, $entityName) {
        $this->entities = $entities;
        $this->entityName = $entityName;
    }

    /**
     * Retrieves all entities.
     *
     * @param array|string   $scopes an optional array of query scopes
     * @return mixed
     */

    public function all($scopes = []) {
        return $this->createQueryBuilder()->getQuery()->getResult();
    }

    /**
     * Retrieves a single entity.
     *
     * @param integer       $id
     * @param array|string  $scopes an optional array of query scopes
     * @return object
     * @throws NoResultException if no result was found
     */

    public function find($id, $scopes = []) {
        try {
            return $this->createScopedQueryBuilder($scopes)
                ->andWhere('o.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

        } catch(DoctrineNoResultException $e) {
            throw new NoResultException($e);
        }
    }

    /**
     * Creates a new entity
     *
     * @return object
     */

    public function make() {
        return new $this->entityName();
    }

    /**
     * Persists an entity.
     *
     * @param object $entity
     * @return void
     */

    public function persist($entity) {
        $this->entities->persist($entity);
        $this->entities->flush();
    }

    /**
     * Deletes an entity.
     *
     * @param object $entity
     * @return void
     */

    public function delete($entity) {
        $this->entities->remove($entity);
        $this->entities->flush();
    }

    /**
     * Creates a new QueryBuilder instance that is pre-populated for this entity name.
     * Applies scopes to the query builder as well.
     *
     * @param array  $scopes
     * @param string $alias
     * @param string $indexBy The index for the from.
     * @return QueryBuilder
     */

    protected function createScopedQueryBuilder($scopes, $alias = 'o', $indexBy = null) {
        $qb = $this->createQueryBuilder($alias, $indexBy);
        foreach((array) $scopes as $scope) {
            $method = 'scope' . ucfirst($scope);
            if(method_exists($this, $method)) {
                $qb = $this->{$method}($qb);
            } else {
                throw new InvalidArgumentException('Scope \'' . $scope . '\' not found');
            }
        }
        return $qb;
    }

    /**
     * Creates a new QueryBuilder instance that is pre-populated for this entity name.
     *
     * @param string $alias
     * @param string $indexBy The index for the from.
     * @return QueryBuilder
     */

    protected function createQueryBuilder($alias = 'o', $indexBy = null) {
        return $this->entities->createQueryBuilder()
                    ->select($alias)
                    ->from($this->entityName, $alias, $indexBy);
    }

}
