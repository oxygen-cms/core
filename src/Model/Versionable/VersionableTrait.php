<?php

namespace Oxygen\Core\Model\Versionable;

use Carbon\Carbon;

trait VersionableTrait {

    /**
     * Interval in hours between new versions.
     *
     * @var integer
     */

    public static $versionInterval = 24;

    /**
     * Boot the versionable trait for a model.
     *
     * @return void
     */

    public static function bootVersionableTrait() {
        static::addGlobalScope(new HeadVersionScope());
        static::observe(new VersionableObserver());
    }

    /**
     * Returns all the versions of the Resource.
     *
     * @return Relation|QueryBuilder
     */

    public function versions() {
        return $this
            ->newQuery()
            ->withTrashed()
            ->withVersions()
            ->where($this->getVersionHeadColumn(), '=', $this->getHeadKey());
    }

    /**
     * Determines if any versions exist for the model
     *
     * @return boolean
     */

    public function hasVersions() {
        return $this->versions()->count() > 0 ? true : false;
    }

    /**
     * Whether the current version is the head version.
     *
     * @return boolean
     */

    public function isHead() {
        return is_null($this->{$this->getVersionHeadColumn()});
    }

    /**
     * Returns the primary key of the head.
     *
     * @return integer
     */

    public function getHeadKey() {
        return $this->isHead() ? $this->getKey() : $this->{$this->getVersionHeadColumn()};
    }

    /**
     * Returns the head version.
     *
     * @return Model
     */

    public function getHead() {
        return $this->isHead() ? $this : $this->withTrashed()->where($this->getKeyName(), $this->getHeadKey())->firstOrFail();
    }

    /**
     * Get a new query builder that includes versions.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withVersions() {
        return (new static)->newQueryWithoutScope(new HeadVersionScope());
    }

    /**
     * Get a new query builder that only includes versions.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */

    public static function onlyVersions() {
        $instance = new static;

        $column = $instance->getQualifiedVersionHeadColumn();

        return $instance->newQueryWithoutScope(new HeadVersionScope())->whereNotNull($column);
    }

    /**
     * Save the model to the database.
     * If there hasn't been a new version
     * for a while, we'll create a new one.
     *
     * @param  array  $options
     * @return bool
     */

    public function save(array $options = array()) {
        $result = parent::save($options);

        $version = array_get($options, 'version', 'guess');

        if($version === 'new' || ($version === 'guess' && $this->needsNewVersion())) {
            $this->makeNewVersion();
        }

        return $result;
    }

    /**
     * Determines if a new version needs to be created.
     *
     * @return boolean
     */

    public function needsNewVersion() {
        if(!$this->isHead()) {
            return false;
        }

        if(!$this->hasVersions()) {
            return true;
        }

        $oldTimestamp = $this->versions()->first()->updated_at;
        $newTimestamp = Carbon::now();
        $diff = $newTimestamp->diffInHours($oldTimestamp);

        if($diff >= self::$versionInterval) {
            return true;
        }
    }

    /**
     * Makes a new version.
     *
     * @return Eloquent the new version
     */

    public function makeNewVersion() {
        $newVersion = $this->replicate();
        $newVersion->{$this->getVersionHeadColumn()} = $this->getHeadKey();
        $newVersion->save();
        return $newVersion;
    }

    /**
     * Makes a version the head version.
     *
     * @return boolean
     */

    public function makeHead() {
        if($this->isHead()) {
            return false;
        }

        // make all other versions point to this version
        $this->versions()->update([$this->getVersionHeadColumn() => $this->getKey()]);

        // remove old head
        $head = $this->getHead();
        $head->{$this->getVersionHeadColumn()} = $this->getKey();
        $head->save();

        // make the current version the head
        $this->{$this->getVersionHeadColumn()} = null;
        $this->save();

        return true;
    }

    /**
     * Get the name of the "version head" column.
     *
     * @return string
     */

    public function getVersionHeadColumn() {
        return defined('static::VERSION_HEAD') ? static::VERSION_HEAD : 'version_head';
    }

    /**
     * Get the fully qualified "version head" column.
     *
     * @return string
     */

    public function getQualifiedVersionHeadColumn() {
        return $this->getTable() . '.' . $this->getVersionHeadColumn();
    }

}