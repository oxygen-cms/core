<?php

namespace Oxygen\Core\View;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Compilers\Compiler;
use Illuminate\View\Compilers\CompilerInterface;
use ReflectionClass;

class BladeStringCompiler extends Compiler implements CompilerInterface {

    /**
     * The Blade compiler.
     *
     * @var \Illuminate\View\Compilers\BladeCompiler
     */

    protected $compiler;

    protected $reflect;

    /**
     * Create a new compiler instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem        $files
     * @param  \Illuminate\View\Compilers\BladeCompiler $compiler
     * @param  string                                   $cachePath
     */
    public function __construct(Filesystem $files, BladeCompiler $compiler, $cachePath) {
        parent::__construct($files, $cachePath);
        $this->blade = $compiler;
        $this->reflect = new ReflectionClass('Illuminate\View\Compilers\BladeCompiler');
    }

    /**
     * Compile the view at the given path.
     *
     * @param  \stdClass $info
     * @return void
     */
    public function compile($info) {
        // resets the footer (eg: layouts)
        $property = $this->reflect->getProperty("footer");
        $property->setAccessible(true);
        $property->setValue($this->blade, []);

        $contents = $this->blade->compileString($info->contents);

        if(!is_null($this->cachePath)) {
            $this->files->put($this->getCompiledPath($info), $contents);
        }
    }

    /**
     * Get the path to the compiled version of a view.
     *
     * @param  \stdClass $info
     * @return string
     */
    public function getCompiledPath($info) {
        return $this->cachePath . '/' . md5($info->path);
    }

    /**
     * Determine if the view at the given path is expired.
     *
     * @param  \stdClass $info
     * @return bool
     */
    public function isExpired($info) {
        $compiled = $this->getCompiledPath($info);

        // If the compiled file doesn't exist we will indicate that the view is expired
        // so that it can be re-compiled. Else, we will verify the last modification
        // of the views is less than the modification times of the compiled views.
        if(!$this->cachePath || !$this->files->exists($compiled) || $info->lastModified === 0) {
            return true;
        }

        return $info->lastModified >= $this->files->lastModified($compiled);
    }

}
