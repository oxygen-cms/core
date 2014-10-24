<?php

namespace Oxygen\Core\Database;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint extends BaseBlueprint {

    /**
     * Add a "version_head" column to the table, allowing it to be used with Versionable resources.
     *
     * @return \Illuminate\Support\Fluent
     */

    public function versionable() {
        return $this->integer('version_head')->nullable();
    }

    /**
     * Drops the "version_head" column
     *
     * @return void
     */

    public function dropVersionable() {
        $this->dropColumn('version_head');
    }

}