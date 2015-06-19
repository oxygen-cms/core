<?php

namespace Oxygen\Core\Blueprint;

use Exception;
use DirectoryIterator;

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
     * Laravel Config.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */

    protected $config;

    /**
     * Laravel Router.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */

    protected $router;

    /**
     * The default base URI for blueprints.
     *
     * @var string
     */
    protected $baseURI;

    /**
     * Constructs the BlueprintManager.
     *
     * @param \Oxygen\Core\Html\Navigation\Navigation  $navigation
     * @param \Oxygen\Core\Contracts\Routing\Registrar $router
     * @param string                                   $baseURI
     */
    public function __construct(Navigation $navigation, Router $router, $baseURI) {
        $this->navigation = $navigation;
        $this->router = $router;
        $this->baseURI = $baseURI;
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
        $blueprint = new Blueprint($name, $this->baseURI);
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
