<?php

namespace Oxygen\Core\Model\Versionable;

use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Database\Eloquent\Builder;

class HeadVersionScope implements ScopeInterface {

    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['WithVersions', 'OnlyVersions'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */

    public function apply(Builder $builder) {
        $builder->whereNull($builder->getModel()->getQualifiedVersionHeadColumn());

        $this->extend($builder);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */

    public function remove(Builder $builder) {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where)
        {
            // If the where clause is a soft delete date constraint, we will remove it from
            // the query and reset the keys on the wheres. This allows this developer to
            // include deleted model in a relationship result set that is lazy loaded.
            if ($this->isHeadVersionConstraint($where, $builder->getModel()->getQualifiedVersionHeadColumn()))
            {
                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */

    public function extend(Builder $builder) {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * Add the with-versions extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */

    protected function addWithVersions(Builder $builder) {
        $builder->macro('withVersions', function(Builder $builder) {
            $this->remove($builder);
            return $builder;
        });
    }

    /**
     * Add the only-versions extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */

    protected function addOnlyVersions(Builder $builder) {
        $builder->macro('onlyVersions', function(Builder $builder) {
            $this->remove($builder);

            $builder->getQuery()->whereNotNull($builder->getModel()->getQualifiedVersionHeadColumn());

            return $builder;
        });
    }

    /**
     * Determine if the given where clause is a soft delete constraint.
     *
     * @param  array   $where
     * @param  string  $column
     * @return bool
     */

    protected function isHeadVersionConstraint(array $where, $column) {
        return $where['type'] == 'Null' && $where['column'] == $column;
    }

}