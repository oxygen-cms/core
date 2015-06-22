<?php

namespace Oxygen\Core\Blueprint;

use Exception;
use DirectoryIterator;

use Oxygen\Core\Contracts\CoreConfiguration;
use Oxygen\Core\Html\Navigation\Navigation;

use Illuminate\Contracts\Config\Repository as Config;
use Oxygen\Core\Contracts\Routing\Registrar as Router;

class BlueprintManager {

    /**
     * The array of Oxygen\Core\Blueprint\Blueprint objects.
     *
     * @var array
     */

    protected $blueprints = [];

    /**
     * Main navigation instance.
     *
     * @var Navigation
     */

    protected $navigation;

    /**
     * The configuration instance.
     *
     * @var CoreConfiguration
     */
    protected $config;

    /**
     * Constructs the BlueprintManager.
     *
     * @param \Oxygen\Core\Html\Navigation\Navigation  $navigation
     * @param CoreConfiguration                        $config
     */
    public function __construct(Navigation $navigation, CoreConfiguration $config) {
        $this->navigation = $navigation;
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
            if($file->isFile() && ends_with($file->getFilename(), '.php')) {
                require $directory . '/' . $file->getFilename();
            }
        }
    }

    /**
     * Constructs the BlueprintManager.
     *
     * @param \Oxygen\Core\Contracts\Routing\Registrar $router
     */
    public function registerRoutes(Router $router) {
        $router->pattern('id', '[0-9]+');

        foreach($this->all() as $blueprint) {
            $router->blueprint($blueprint);
        }
    }

    /**
     * Make a new Blueprint.
     *
     * @param string $name display name of the blueprint
     * @param callable $callback
     * @return void
     */
    public function make($name, callable $callback) {
        $blueprint = new Blueprint($name, $this->config->getAdminURIPrefix());
        $callback($blueprint);
        $this->blueprints[$blueprint->getName()] = $blueprint;

        if($blueprint->hasPrimaryToolbarItem()) {
            $this->navigation->add($blueprint->getPrimaryToolbarItem());
        }
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
     * @param string $name display name of the blueprint
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
