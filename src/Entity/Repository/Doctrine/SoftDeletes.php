<?php

namespace Oxygen\Core\Entity\Repository\Doctrine;

use DateTime;
use Doctrine\ORM\QueryBuilder;

trait SoftDeletes {

    /**
     * Filters out trashed entities.
     *
     * @param QueryBuilder $query
     * @return QueryBuilder
     */

    protected function scopeExcludeTrashed(QueryBuilder $query) {
        return $query->andWhere('o.deletedAt is NULL');
    }

    /**
     * Filters everything except trashed entities.
     *
     * @param QueryBuilder $query
     * @return QueryBuilder
     */

    protected function scopeOnlyTrashed(QueryBuilder $query) {
        $time = new DateTime();

        return $query
            ->andWhere('o.deletedAt is not NULL')
            ->andWhere(':currentTimestamp > o.deletedAt')
            ->setParameter('currentTimestamp', $time->format('Y-m-d H:i:s'));
    }

}
