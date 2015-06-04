<?php

namespace Oxygen\Core\Blueprint;

use Exception;
use DirectoryIterator;

use Oxygen\Core\Html\Navigation\Navigation;
use Oxygen\Core\Action\Factory\ActionFactory;

use Illuminate\Config\Repository as Config;
use Illuminate\Routing\Router;

class Manager {

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
     * Laravel Config.
     *
     * @var Config
     */

    protected $config;

    /**
     * Laravel Router.
     *
     * @var Router
     */

    protected $router;

    /**
     * Constructs the BlueprintManager.
     *
     * @param Navigation $navigation
     */
    public function __construct(Navigation $navigation, Config $config, Router $router) {
        $this->navigation = $navigation;
        $this->config = $config;
        $this->router = $router;
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
     * @return void
     */
    public function registerRoutes() {
        $this->router->pattern('id', '[0-9]+');

        foreach($this->all() as $blueprint) {
            $this->router->blueprint($blueprint);
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
        $blueprint = new Blueprint($name, $this->config->get('oxygen/core::config.baseURI'));
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
     * @throws \Exception if the Blueprint can't be found
     */
    public function get($name) {
        if(isset($this->blueprints[$name])) {
            return $this->blueprints[$name];
        } else {
            throw new Exception('Blueprint not found: "' . $name . '"');
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
