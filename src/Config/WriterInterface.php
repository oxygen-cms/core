<?php

namespace Oxygen\Core\Config;

interface WriterInterface {

    /**
     * Writes the given configuration item.
     *
     * @param  string  $item
     * @param  mixed   $value
     * @param  string  $environment
     * @param  string  $group
     * @param  string  $namespace
     * @return void
     */

    public function write($item, $value, $environment, $group, $namespace = null);

    /**
     * Registers a package with the FileWriter.
     *
     * @param string $package usually {vendor}/{package}
     * @param string $namespace usually just {package}
     */

    public function addPackage($package, $namespace);

}