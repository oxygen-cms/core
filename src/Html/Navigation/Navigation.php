<?php

namespace Oxygen\Core\Html\Navigation;

use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Html\Toolbar\Toolbar;
use Oxygen\Core\Html\Toolbar\ToolbarItem;
use Oxygen\Core\Html\Toolbar\SpacerToolbarItem;
use Oxygen\Core\Html\RenderableTrait;

class Navigation implements RenderableInterface {

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
     * Callbacks to set the order of navigation items.
     *
     * @var array
     */
    protected $lazyOrders;

    /**
     * Construct the Navigation.
     */
    public function __construct() {
        $this->toolbars = [
            'primary'   => new Toolbar(),
            'secondary' => new Toolbar()
        ];
        $itemsPool = [];
        $this->toolbars['primary']->setSharedItemsPool($itemsPool);
        $this->toolbars['secondary']->setSharedItemsPool($itemsPool);
        $this->spacer = new SpacerToolbarItem();

        $this->lazyOrders = [
            self::PRIMARY => [],
            self::SECONDARY => []
        ];
    }

    /**
     * Returns all toolbar items.
     *
     * @param integer $toolbar Which toolbar to use
     * @return array
     */
    public function all($toolbar = self::PRIMARY) {
        $this->loadLazyOrders($toolbar);

        return $this->toolbars[$toolbar]->getItems();
    }

    /**
     * Adds a ToolbarItem to the toolbar.
     *
     * @param ToolbarItem $item
     * @return void
     */
    public function add(ToolbarItem $item) {
        $this->toolbars['primary']->addItem($item);
    }

    /**
     * Orders the toolbar.
     *
     * @param integer $toolbar Which toolbar to use
     * @param array|callable $keys
     * @return void
     */
    public function order($toolbar, $keys) {
        if(is_callable($keys)) {
            $this->addLazyOrder($toolbar, $keys);
        } else {
            $this->toolbars[$toolbar]->setOrder($keys);
        }
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

    /**
     * Loads orders of the navigation items.
     *
     * @param string $toolbar
     * @return void
     */
    protected function loadLazyOrders($toolbar) {
        foreach($this->lazyOrders[$toolbar] as $callback) {
            $this->order($toolbar, $callback());
        }
    }

    /**
     * Adds a lazy ordering function.
     *
     * @param string $toolbar
     * @param callable $callback
     * @return void
     */
    protected function addLazyOrder($toolbar, $callback) {
        $this->lazyOrders[$toolbar][] = $callback;
    }

}
