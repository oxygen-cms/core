<?php

namespace Oxygen\Core\Html\Navigation;

use Oxygen\Core\Html\Toolbar\Toolbar;
use Oxygen\Core\Html\Toolbar\ToolbarItem;
use Oxygen\Core\Html\Toolbar\SpacerToolbarItem;
use Oxygen\Core\Html\RenderableTrait;

class Navigation {

    use RenderableTrait;

    /**
     * Primary toolbar.
     *
     * @var integer
     */

    const PRIMARY = 'primary';

    /**
     * Secondary toolbar.
     *
     * @var integer
     */

    const SECONDARY = 'secondary';

    /**
     * SpacerToolbarItem instance. Reduces extra object creation.
     *
     * @var SpacerToolbarItem
     */

    public $spacer;

    /**
     * Toolbar instances containing ToolbarItems
     *
     * @var array
     */

    protected $toolbars;

    /**
     * Construct the Navigation.
     */

    public function __construct() {
        $this->toolbars = [
            'primary'   => new Toolbar(),
            'secondary' => new Toolbar()
        ];
        $this->spacer = new SpacerToolbarItem();
    }

    /**
     * Returns all toolbar items.
     *
     * @param integer $toolbar Which toolbar to use
     * @return array
     */

    public function all($toolbar = self::PRIMARY) {
        return $this->toolbars[$toolbar]->getItems();
    }

    /**
     * Adds a ToolbarItem to a toolbar.
     *
     * @param ToolbarItem $item
     * @param integer $toolbar Which toolbar to use
     * @return void
     */

    public function add(ToolbarItem $item, $toolbar = self::PRIMARY) {
        $this->toolbars[$toolbar]->addItem($item);
    }

    /**
     * Orders the toolbar.
     *
     * @param integer $toolbar Which toolbar to use
     * @param array $keys
     * @return void
     */

    public function order($toolbar, array $keys) {
        $this->toolbars[$toolbar]->setOrder($keys);
    }

    /**
     * Returns the underlying toolbar.
     *
     * @param integer $toolbar Which toolbar to use
     * @return Toolbar
     */

    public function getToolbar($toolbar) {
        return $this->toolbars[$toolbar];
    }

}