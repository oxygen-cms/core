<?php


namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Html\RenderableTrait;

class Row {

    use RenderableTrait;

    protected $items;

    public $isFooter;

    public function __construct(array $items) {
        $this->items = $items;
    }

    /**
     * Adds an item to the row.
     *
     * @param \Oxygen\Core\Html\RenderableInterface $item
     */
    public function addItem(RenderableInterface $item) {
        $this->items[] = $item;
    }

    /**
     * Returns the items in the row.
     *
     * @return array
     */
    public function getItems() {
        return $this->items;
    }

}
