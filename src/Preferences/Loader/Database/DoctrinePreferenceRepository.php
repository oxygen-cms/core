<?php

namespace Oxygen\Core\Preferences\Loader\Database;

use Doctrine\ORM\NoResultException as DoctrineNoResultException;
use Oxygen\Data\Exception\NoResultException;
use Oxygen\Data\Repository\Doctrine\Repository;
use Oxygen\Core\Preferences\Loader\PreferenceRepositoryInterface;

class DoctrinePreferenceRepository extends Repository implements PreferenceRepositoryInterface {

    /**
     * The name of the entity.
     *
     * @var string
     */

    protected $entityName = PreferenceItem::class;

    /**
     * Finds an preference item based upon the key.
     *
     * @param string $key
     * @return PreferenceItem
     * @throws NoResultException if the key doesn't exist
     */
    public function findByKey($key) {
        $q = $this->getQuery(
            $this->createSelectQuery()
                 ->andWhere('o.key = :key')
                 ->setParameter('key', $key)
        );

        try {
            return $q->getSingleResult();
        } catch(DoctrineNoResultException $e) {
            throw $this->makeNoResultException($e, $q);
        }
    }
}
