<?php

namespace Oxygen\Core\Database;

class AutomaticMigrator {

    /**
     * Array of folders containing migrations.
     *
     * @var array
     */

    protected $paths;

    /**
     * Constructs the automatic migrator.
     *
     * @param $vendorDir
     */

    public function __construct($vendorDir) {
        $this->vendorDir = $vendorDir;
        $this->paths = [];
    }

    /**
     * Adds a folder of migrations to the AutomaticMigrator
     *
     * @param string      $package
     * @param string|null $path
     */

    public function add($package, $path = null) {
        if($path === null) {
            $path = $this->vendorDir . '/' . $package . '/src/migrations';
        }

        $this->paths[$package] = $path;
    }

    /**
     * Returns the paths.
     *
     * @return array
     */

    public function getPaths() {
        return $this->paths;
    }

}