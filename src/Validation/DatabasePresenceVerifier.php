<?php

namespace Oxygen\Core\Validation;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Validation\DatabasePresenceVerifier as BaseDatabasePresenceVerifier;

class DatabasePresenceVerifier extends BaseDatabasePresenceVerifier {

    /**
     * Create a new database presence verifier.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $db
     * @return void
     */

    public function __construct(ConnectionResolverInterface $db) {
        parent::__construct($db);
    }

    /**
     * Add a "where" clause to the given query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $key
     * @param  string  $extraValue
     * @return void
     */

    protected function addWhere($query, $key, $extraValue) {
        if(is_array($extraValue)) {
            list($operator, $value) = $extraValue;
            $query->where($key, $operator, $value);
        } else {
            if($extraValue === 'NULL') {
                $query->whereNull($key);
            } elseif($extraValue === 'NOT_NULL') {
                $query->whereNotNull($key);
            } else {
                $query->where($key, $extraValue);
            }
        }
    }

}
