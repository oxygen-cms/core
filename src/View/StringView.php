<?php

namespace Oxygen\Core\View;

use Closure;
use Illuminate\Support\Contracts\ArrayableInterface as Arrayable;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\View;
use stdClass;

class StringView extends View {

    /**
     * The contents of the view.
     *
     * @var string
     */
    protected $contents;

    /**
     * The last time the string was modified.
     *
     * @var string
     */

    protected $lastModified;

    /**
     * Create a new view instance.
     *
     * @param  Factory         $factory
     * @param  EngineInterface $engine
     * @param  string          $contents
     * @param  string          $path
     * @param  string          $lastModified
     * @param  array           $data
     */
    public function __construct(
        Factory $factory,
        EngineInterface $engine,
        $contents,
        $path,
        $lastModified,
        $data = []
    ) {
        $this->contents = $contents;
        $this->path = $path;
        $this->lastModified = $lastModified;
        $this->engine = $engine;
        $this->factory = $factory;
        $this->info = $this->gatherInfo();

        $this->data = $data instanceof Arrayable ? $data->toArray() : (array)$data;
    }

    /**
     * Get the string contents of the view.
     *
     * @param callable $callback
     * @return string
     */
    public function render(callable $callback = null) {
        $contents = $this->renderContents();

        $response = isset($callback) ? $callback($this, $contents) : null;

        // Once we have the contents of the view, we will flush the sections if we are
        // done rendering all views so that there is nothing left hanging over when
        // another view is rendered in the future by the application developers.
        $this->factory->flushSectionsIfDoneRendering();

        return $response ?: $contents;
    }

    /**
     * Get the contents of the view instance.
     *
     * @return string
     */
    protected function renderContents() {
        // We will keep track of the amount of views being rendered so we can flush
        // the section after the complete rendering operation is done. This will
        // clear out the sections for any separate views that may be rendered.
        $this->factory->incrementRender();

        $contents = $this->getContents();

        // Once we've finished rendering the view, we'll decrement the render count
        // so that each sections get flushed out next time a view is created and
        // no old sections are staying around in the memory of an environment.
        $this->factory->decrementRender();

        return $contents;
    }

    /**
     * Return the rendered contents of the view.
     *
     * @return string
     */
    protected function getContents() {
        return $this->engine->get($this->gatherInfo(), $this->gatherData());
    }

    /**
     * Gathers the information about the view.
     *
     * @return \stdClass
     */
    protected function gatherInfo() {
        $info = new stdClass();
        $info->contents = $this->contents;
        $info->path = $this->path;
        $info->lastModified = $this->lastModified;

        return $info;
    }

}
