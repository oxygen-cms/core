<?php

namespace Oxygen\Core\Html\Toolbar;

use Exception;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Html\RendererInterface;

class ButtonToolbarItem extends ActionToolbarItem {

    use RenderableTrait {
        RenderableTrait::render as baseRender;
    }

    /**
     * Label for the button.
     *
     * @var string
     */
    public $label;

    /**
     * Color of the button.
     *
     * @var string
     */
    public $color;

    /**
     * Icon of the button.
     *
     * @var string
     */
    public $icon;

    /**
     * Dialog object to be displayed.
     */
    public $dialog;

    /**
     * Constructs the item.
     *
     * @param string $label the label
     * @param Action $action the action that will be executed
     */
    public function __construct($label, Action $action) {
        parent::__construct($action);
        $this->label          = $label;
        $this->color          = 'white';
        $this->icon           = null;
        $this->dialog         = null;
    }

    /**
     * Returns whether the ToolbarItem has a dialog.
     *
     * @return boolean
     */
    public function hasDialog() {
        return $this->dialog !== null;
    }

    /**
     * Renders the object.
     * Before rendering all 'dynamic callbacks' will be excecuted.
     *
     * @param array             $arguments
     * @param RendererInterface|callable $renderer
     * @throws \Exception
     * @return string the rendered object
     */
    public function render(array $arguments = [], $renderer = null) {
        $this->runDynamicCallbacks($arguments);

        return $this->baseRender($arguments, $renderer);
    }

}
