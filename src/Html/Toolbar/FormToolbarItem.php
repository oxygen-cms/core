<?php

namespace Oxygen\Core\Html\Toolbar;

use Exception;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Html\RendererInterface;

class FormToolbarItem extends ActionToolbarItem {

    use RenderableTrait {
        RenderableTrait::render as baseRender;
    }

    /**
     * Form fields.
     *
     * @var array
     */

    public $fields;

    /**
     * Constructs the item.
     *
     * @param \Oxygen\Core\Action\Action $action
     */

    public function __construct(Action $action) {
        parent::__construct($action);
        $this->fields = [];
    }

    /**
     * Determines if the ActionToolbarItem will link to the current page.
     *
     * @param array $arguments
     * @return boolean
     */

    public function linksToCurrentPage(array $arguments) {
        return false;
    }

    /**
     * Renders the object.
     * Before rendering all 'dynamic callbacks' will be excecuted.
     *
     * @param array             $arguments
     * @param RendererInterface|callable $renderer
     * @throws Exception
     * @return string the rendered object
     */

    public function render(array $arguments = [], $renderer = null) {
        $this->runDynamicCallbacks($arguments);

        return $this->baseRender($arguments, $renderer);
    }

}
