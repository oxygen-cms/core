<?php

namespace Oxygen\Core\Html\Toolbar;

use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Html\RendererInterface;
use Illuminate\Support\Str;

class DropdownToolbarItem implements ToolbarItem {

    use RenderableTrait {
        RenderableTrait::render as baseRender;
    }

    /**
     * The list of items
     *
     * @var array
     */
    public $items;

    /**
     * The list of items that have passed the shouldRender() test.
     *
     * @var array
     */
    public $itemsToDisplay;

    /**
     * Color of the button.
     *
     * @var string
     */
    public $color;

    /**
     * The label of the dropdown button
     *
     * @var string|ToolbarItem
     */
    public $label;

    /**
     * A primary action for the toolbar item.
     *
     * @var ToolbarItem
     */
    public $button;

    /**
     * Icon of the dropdown button.
     *
     * @var string
     */
    public $icon;

    /**
     * Unique identifier of the dropdown.
     *
     * @var string
     */
    public $identifier;

    /**
     * Stores if the button should be rendered.
     *
     * @var boolean
     */
    public $shouldRenderButton;

    /**
     * Constructs the DropdownToolbarItem.
     *
     * @param string $label
     * @param array  $items
     */
    public function __construct($label, array $items = []) {
        $this->items = $items;
        $this->itemsToDisplay = [];
        $this->label = $label;
        $this->color = 'white';
        $this->icon = 'angle-down';
        $this->identifier = Str::camel($this->label);
    }

    /**
     * Adds an item to the DropdownToolbarItem.
     *
     * @param ToolbarItem $item
     */
    public function addItem(ToolbarItem $item) {
        $this->items[] = $item;
    }

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * Determines if the button should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */
    public function shouldRender(array $arguments = []) {
        foreach($this->items as $item) {
            if($item->shouldRender($arguments)) {
                $this->itemsToDisplay[] = $item;
            }
        }

        $this->shouldRenderButton = $this->button !== null && $this->button->shouldRender($arguments);

        // if there any items in the dropdown then we'll display it
        // or if the primary action should be displayed
        return !empty($this->itemsToDisplay) || $this->shouldRenderButton;
    }

    /**
     * Renders the object.
     *
     * @param array                      $arguments
     * @param RendererInterface|callable $renderer
     * @throws \Exception
     * @return string the rendered object
     */
    public function render(array $arguments = [], $renderer = null) {
        if(empty($this->itemsToDisplay)) {
            return $this->button->render($arguments);
        }

        return $this->baseRender($arguments, $renderer);
    }

}
