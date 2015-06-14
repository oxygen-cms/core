<?php

namespace Oxygen\Core\Config;

use Illuminate\Config\Repository as BaseRepository;
use Illuminate\Config\LoaderInterface;
use Oxygen\Core\Contracts\Config\WritableRepository;

class Repository extends BaseRepository implements WritableRepository {

    /**
     * The writer implementation.
     *
     * @var WriterInterface
     */

    protected $writer;

    /**
     * Create a new configuration repository.
     *
     * @param   LoaderInterface $loader
     * @param   WriterInterface $writer
     * @param   string          $environment
     */
    public function __construct(LoaderInterface $loader, WriterInterface $writer, $environment) {
        parent::__construct($loader, $environment);

        $this->writer = $writer;
    }

    /**
     * Writes the configuration key and value to a file.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function write($key, $value) {
        list($namespace, $group, $item) = $this->parseKey($key);

        $this->writer->write($item, $value, $this->environment, $group, $namespace);

        $this->set($key, $value);
    }

    /**
     * Register a package for cascading configuration.
     *
     * @param  string  $package
     * @param  string  $hint
     * @param  string  $namespace
     * @return void
     */
    public function package($package, $hint, $namespace = null) {
        parent::package($package, $hint, $namespace);

        $namespace = $this->getPackageNamespace($package, $namespace);

        // lets the writer know that config for {namespace} should be written to /app/config/packages/{package}
        $this->writer->addPackage($package, $namespace);
    }

}
