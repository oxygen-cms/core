<?php


namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Html\RenderableTrait;

class Row {

    use RenderableTrait;

    protected $cells;

    public function __construct(array $cells) {
        $this->cells = $cells;
    }

    /**
     * Returns the cells in the row.
     *
     * @return array
     */
    public function getCells() {
        return $this->cells;
    }

}
