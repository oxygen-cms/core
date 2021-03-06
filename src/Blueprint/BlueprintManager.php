<?php

namespace Oxygen\Core\Blueprint;

use DirectoryIterator;
use Oxygen\Core\Support\Str;
use Oxygen\Core\Contracts\CoreConfiguration;
use Oxygen\Core\Contracts\Routing\BlueprintRegistrar;

class BlueprintManager {

    /**
     * The array of Oxygen\Core\Blueprint\Blueprint objects.
     *
     * @var array
     */

    protected $blueprints = [];

    /**
     * The configuration instance.
     *
     * @var CoreConfiguration
     */
    protected $config;

    /**
     * Constructs the BlueprintManager.
     *
     * @param CoreConfiguration                       $config
     */
    public function __construct(CoreConfiguration $config) {
        $this->config = $config;
    }

    /**
     * Loads a directory of files.
     *
     * @param string $directory
     * @return void
     */
    public function loadDirectory($directory) {
        $iterator = new DirectoryIterator($directory);
        foreach($iterator as $file) {
            if($file->isFile() && Str::endsWith($file->getFilename(), '.php')) {
                require $directory . '/' . $file->getFilename();
            }
        }
    }

    /**
     * Constructs the BlueprintManager.
     *
     * @param BlueprintRegistrar $registrar
     */
    public function registerRoutes(BlueprintRegistrar $registrar) {
        $registrar->getRouter()->pattern('id', '[0-9]+');

        foreach($this->all() as $blueprint) {
            $registrar->blueprint($blueprint);
        }
    }

    /**
     * Constructs the BlueprintManager.
     *
     * @param BlueprintRegistrar $registrar
     */
    public function registerFinalRoutes(BlueprintRegistrar $registrar) {
        foreach($this->all() as $blueprint) {
            $registrar->blueprintFinal($blueprint);
        }
    }

    /**
     * Make a new Blueprint.
     *
     * @param string   $name display name of the blueprint
     * @param callable $callback
     * @return void
     */
    public function make($name, callable $callback) {
        $blueprint = new Blueprint($name, $this->config->getAdminURIPrefix());
        $callback($blueprint);
        $this->blueprints[$blueprint->getName()] = $blueprint;
    }

    /**
     * Get a Blueprint.
     *
     * @param string $name blueprint
     * @return Blueprint
     * @throws BlueprintNotFoundException if the Blueprint can't be found
     */
    public function get($name) {
        if(isset($this->blueprints[$name])) {
            return $this->blueprints[$name];
        } else {
            throw new BlueprintNotFoundException('Blueprint not found: "' . $name . '"');
        }
    }

    /**
     * Edits an existing Blueprint.
     *
     * @param string   $name display name of the blueprint
     * @param callable $callback
     * @return void
     */
    public function edit($name, callable $callback) {
        $callback($this->get($name));
    }

    /**
     * Remove a Blueprint.
     *
     * @param string $name name of the blueprint
     * @return void
     */
    public function remove($name) {
        unset($this->blueprints[$name]);
    }

    /**
     * Determines if the given Blueprint exists.
     *
     * @param string $name name of the blueprint
     * @return boolean
     */
    public function exists($name) {
        return isset($this->blueprints[$name]);
    }

    /**
     * Get all the Blueprint objects.
     *
     * @return array
     */
    public function all() {
        return $this->blueprints;
    }

}
