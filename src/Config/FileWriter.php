<?php

namespace Oxygen\Core\Config;

use InvalidArgumentException;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ConfigPublisher;
use Illuminate\Config\LoaderInterface;

class FileWriter implements WriterInterface {

    /**
     * The filesystem.
     *
     * @var Filesystem
     */

    protected $files;

    /**
     * LoaderInterface implementation.
     *
     * @var LoaderInterface
     */

    protected $loader;

    /**
     * The config Rewriter.
     *
     * @var Rewriter
     */

    protected $rewriter;

    /**
     * The default configuration path.
     *
     * @var string
     */

    protected $defaultPath;

    /**
     * The paths to namespaces.
     *
     * @var array
     */

    protected $packages;

    /**
     * Constructs the FileWriter.
     *
     * @param  Filesystem       $files
     * @param  LoaderInterface  $loader
     * @param  Rewriter         $rewriter
     * @param  ConfigPublished  $publisher
     * @param  string           $defaultPath
     * @return void
     */
    public function __construct(Filesystem $filesystem, LoaderInterface $loader, Rewriter $rewriter, ConfigPublisher $publisher, $defaultPath) {
        $this->files            = $filesystem;
        $this->loader           = $loader;
        $this->rewriter         = $rewriter;
        $this->configPublisher  = $publisher;
        $this->defaultPath      = $defaultPath;
        $this->packages         = [];
    }

    /**
     * Writes the given configuration item.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  string  $environment
     * @param  string  $group
     * @param  string  $namespace
     * @param  boolean $writeToPackageSource whether to write the config to /vendor, or to /app/config/packages
     * @return void|boolean
     */
    public function write($key, $value, $environment, $group, $namespace = null, $writeToPackageSource = false) {
        $path = $this->getPath($key, $environment, $group, $namespace, $writeToPackageSource);

        $contents = $this->files->get($path);
        $oldValue = array_get($this->files->getRequire($path), $key, []);

        if($oldValue === $value) {
            return false;
        }

        $newContents = $this->rewriter->rewrite($contents, $key, $oldValue, $value);

        $this->files->put($path, $newContents);
    }

    /**
     * Gets the filepath to the given configuration key.
     *
     * @param  string  $key
     * @param  string  $environment
     * @param  string  $group
     * @param  string  $namespace
     * @param  boolean $writeToPackageSource whether to write the config to /vendor, or to /app/config/packages
     * @param  boolean $publish whether to publish a packages config if it doesn't exist already
     * @return string
     */

    protected function getPath($key, $environment, $group, $namespace, $writeToPackageSource = false, $publish = true) {
        // get the namespace's path
        if($namespace === null) {
            $path = $this->defaultPath;
        } else if($writeToPackageSource) {
            $path = $this->getPackageSourcePath($namespace);
        } else {
            $path = $this->getPackageOverridePath($namespace);
        }

        // get the environment/group
        $file = $path . '/' . $environment . '/' . $group . '.php';
        if($this->files->exists($file) && $this->fileHasKey($file, $key)) {
            return $file;
        }

        $file = $path . '/' . $group . '.php';
        if($this->files->exists($file)) {
            return $file;
        } else if($publish) {
            $this->publishConfig($namespace);
            return $this->getPath($key, $environment, $group, $namespace, $writeToPackageSource, $publish);
        } else {
            throw new InvalidArgumentException('No config file found for namespace "' . $namespace . '" and group "' . $group . '"');
        }
    }

    /**
     * Returns the package name from a namespace
     *
     * @param string $namespace not necessarily the name of the package
     * @return string
     * @throws InvalidArgumentException If the package override path can't be found
     */

    protected function getPackageFromNamespace($namespace) {
        if(isset($this->packages[$namespace])) {
            return $this->packages[$namespace];
        } else {
            throw new InvalidArgumentException('No package override path for namespace "' . $namespace . '"');
        }
    }

    /**
     * Returns the path to the package override config.
     * By default it will be /app/config/packages/{vendor}/{namespace}
     *
     * @param string $namespace not necessarily the name of the package
     * @return string
     * @throws InvalidArgumentException If the package override path can't be found
     */

    protected function getPackageOverridePath($namespace) {
        return $this->defaultPath . '/packages/' . $this->getPackageFromNamespace($namespace);
    }

    /**
     * Returns the path to the package source config.
     * By default it will be /vendor/{vendor}/{namespace}/src/config
     *
     * @param string $namespace
     * @return string
     * @throws InvalidArgumentException If no hint has been defined.
     */

    protected function getPackageSourcePath($namespace) {
        $hints = $this->loader->getNamespaces();

        if(isset($hints[$namespace])) {
            return $hints[$namespace];
        } else {
            throw new InvalidArgumentException('No path hint found for namespace "' . $namespace . '"');
        }
    }

    /**
     * Publishes the config for a given namespace.
     *
     * @param string $namespace
     * @return void
     * @throws InvalidArgumentException If the config can't be published.
     */

    protected function publishConfig($namespace) {
        $source = $this->getPackageSourcePath($namespace);
        $package = $this->getPackageFromNamespace($namespace);
        $this->configPublisher->publish($package, $source);
    }

    /**
     * Checks if the given file has the config key.
     *
     * @param string $path
     * @param string $key
     * @return boolean
     */

    protected function fileHasKey($path, $key) {
        $contents = $this->files->getRequire($path);

        if($key === null || $key === '') {
            return true;
        }

        foreach(explode('.', $key) as $segment) {
            if(isset($contents[$segment])) {
                $contents = $contents[$segment];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Registers a package with the FileWriter.
     *
     * @param string $package usually {vendor}/{package}
     * @param string $namespace usually just {package}
     */
    public function addPackage($package, $namespace) {
        $this->packages[$namespace] = $package;
    }

}
