<?php


namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Html\RenderableTrait;

class Row {

    use RenderableTrait;

    public $useDefaults;

    public $isFooter;

    protected $items;

    /**
     * Custom classes to add to the form.
     *
     * @var array
     */
    protected $extraClasses;

    public function __construct(array $items) {
        $this->items = $items;
        $this->useDefaults = true;
        $this->extraClasses = [];
    }

    /**
     * Adds an item to the row.
     *
     * @param \Oxygen\Core\Html\RenderableInterface|string $item
     */
    public function addItem($item) {
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

    /**
     * Adds a custom class to the row.
     *
     * @param $name
     */
    public function addClass($name) {
        $this->extraClasses[] = $name;
    }

    /**
     * @return array
     */
    public function getExtraClasses() {
        return $this->extraClasses;
    }

}
