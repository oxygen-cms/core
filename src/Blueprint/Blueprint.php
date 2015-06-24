<?php

namespace Oxygen\Core\Blueprint;

use InvalidArgumentException;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Action\Group;
use Oxygen\Core\Action\Factory\AdminActionFactory;
use Oxygen\Core\Html\Toolbar\ToolbarItem;
use Oxygen\Core\Html\Toolbar\Factory\ButtonToolbarItemFactory;
use Oxygen\Core\Form\FieldMetadata;
use Oxygen\Core\Factory\FactoryInterface;
use Oxygen\Core\Support\Str;

class Blueprint {

    /**
     * A singular name.
     */

    const SINGULAR = false;

    /**
     * A plural name
     */

    const PLURAL = true;

    /**
     * Base URI of the Blueprint.
     *
     * @var string
     */
    private $baseURI;

    /**
     * Name of the Blueprint.
     *
     * @var string
     */
    protected $name;

    /**
     * Display names of the Blueprint.
     *
     * @var array
     */
    protected $displayNames;

    /**
     * Controller of the Blueprint.
     *
     * @var string
     */
    protected $controller;

    /**
     * The primary toolbar item, to be displayed on menus.
     *
     * @var string
     */
    protected $primaryToolbarItem;

    /**
     * The icon of the resource.
     *
     * @var string
     */
    protected $icon;

    /**
     * Actions of the model.
     *
     * @var array
     */
    protected $actions;

    /**
     * Orders of toolbars.
     *
     * @var array
     */
    protected $toolbarOrders;

    /**
     * Pool of toolbar items.
     *
     * @var array
     */
    protected $toolbarItems;

    /**
     * Default FactoryInterface instance, used when creating
     * actions if an custom factory is not provided.
     *
     * @var FactoryInterface
     */
    protected $defaultActionFactory;

    /**
     * Default FactoryInterface instance, used when creating
     * toolbar items if an custom factory is not provided.
     *
     * @var FactoryInterface
     */
    protected $defaultToolbarItemFactory;

    /**
     * Constructs a Blueprint object
     *
     * @param string $name the name of the Blueprint
     * @param string $baseURI
     */
    public function __construct($name, $baseURI = '/') {
        $this->baseURI = $baseURI;

        $this->name          = $name;
        $this->displayNames  = [
            'singular'  => Str::camelToWords($name),
            'plural'    => Str::plural(Str::camelToWords($name))
        ];
        $this->controller    = null;
        $this->primaryToolbarItem = null;
        $this->icon          = null;
        $this->actions       = [];
        $this->toolbarOrders = [
            'section' => [],
            'item'    => []
        ];
        $this->toolbarItems = [];
        $this->defaultActionFactory = new AdminActionFactory();
        $this->defaultToolbarItemFactory = new ButtonToolbarItemFactory();
        $this->baseURI = $baseURI;
    }

    /**
     * Returns the name of the Blueprint.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the name of the Blueprint.
     *
     * @param string $name new name of the Blueprint
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Sets both the singular and plural names of the Blueprint.
     *
     * @param array $names
     */
    public function setDisplayNames(array $names) {
        $this->displayNames = $names;
    }

    /**
     * Set the display name of the Blueprint.
     *
     * @param string   $name
     * @param boolean  $type
     * @return void
     */
    public function setDisplayName($name, $type = self::SINGULAR) {
        if($type === self::SINGULAR) {
            $this->displayNames['singular'] = $name;
        } else if($type === self::PLURAL) {
            $this->displayNames['plural'] = $name;
        }
    }

    /**
     * Get the display name of the model.
     *
     * @param boolean $type
     * @throws InvalidArgumentException If $type is not valid
     * @return string
     */
    public function getDisplayName($type = self::SINGULAR) {
        if($type === self::SINGULAR) {
            return $this->displayNames['singular'];
        } else if($type === self::PLURAL) {
            return $this->displayNames['plural'];
        } else {
            throw new InvalidArgumentException('Invalid Display Name Type: "' . $type . '"');
        }
    }

    /**
     * Returns the camel cased name of the Blueprint.
     * Used for route names.
     * If the $actionName parameter is provided
     * then it will be concatenated onto the end of the route.
     *
     * @param string $actionName
     * @return string
     */
    public function getRouteName($actionName = null) {
        $name = Str::camel($this->getDisplayName(self::PLURAL));
        return $actionName == null ? $name : $name . '.' . $actionName;
    }

    /**
     * Returns the slugified version of the Blueprint name.
     * Used as a prefix for all admin routes.
     *
     * @return string
     */
    public function getRoutePattern() {
        $slug = Str::slug($this->getDisplayName(self::PLURAL));
        return $this->baseURI !== '/' ? $this->baseURI . '/' . $slug : $slug;
    }

    /**
     * Determines if a controller has been set.
     *
     * @return boolean
     */
    public function hasController() {
        return ($this->controller !== null);
    }

    /**
     * Returns the controller of the Blueprint.
     *
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Sets the controller of the Blueprint.
     *
     * @param string $controller
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * Determine if the primary toolbar item has been set.
     *
     * @return boolean
     */
    public function hasPrimaryToolbarItem() {
        return ($this->primaryToolbarItem !== null);
    }

    /**
     * Get the primary toolbar item.
     *
     * @return ToolbarItem
     */
    public function getPrimaryToolbarItem() {
        if($this->hasPrimaryToolbarItem()) {
            return $this->getToolbarItem($this->primaryToolbarItem);
        } else {
            return null;
        }
    }

    /**
     * Set the primary toolbar item.
     *
     * @param string $name
     * @return void
     */
    public function setPrimaryToolbarItem($name) {
        $this->primaryToolbarItem = $name;
    }

    /**
     * Sets the icon of the blueprint.
     *
     * @param string $icon the icon
     * @return void
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * Returns the icon of the blueprint.
     *
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Get an action by its name.
     *
     * @param string $name
     * @return Action
     */
    public function getAction($name) {
        if(isset($this->actions[$name])) {
            return $this->actions[$name];
        } else {
            throw new InvalidArgumentException('Action does not exist: ' . $name);
        }
    }

    /**
     * Get all the actions.
     *
     * @return array
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Add an action.
     *
     * @param Action $action
     * @return void
     */
    public function addAction(Action $action) {
        $this->actions[$action->name] = $action;
    }

    /**
     * Adds an action.
     *
     * @param array $parameters
     * @param FactoryInterface $factory Optional FactoryInterface
     * @return Action
     */
    public function makeAction(array $parameters, FactoryInterface $factory = null) {
        if($factory === null) {
            $factory = $this->getDefaultActionFactory();
        }

        if(!isset($parameters['group'])) {
            $parameters['group'] = new Group($this->getRouteName(), $this->getRoutePattern());
        }

        $action = $factory->create($parameters, $this->getController());

        $this->addAction($action);

        return $action;
    }

    /**
     * Remove an action.
     *
     * @param string $name
     * @return void
     */
    public function removeAction($name) {
        unset($this->actions[$name]);
    }

    /**
     * Sets the default FactoryInterface
     * to be used when creating Actions.
     *
     * @param FactoryInterface $factory
     * @return void
     */
    public function setDefaultActionFactory(FactoryInterface $factory) {
        $this->defaultActionFactory = $factory;
    }

    /**
     * Returns the default FactoryInterface
     * to be used when creating Actions.
     *
     * @return FactoryInterface
     */
    public function getDefaultActionFactory() {
        return $this->defaultActionFactory;
    }

    /**
     * Gets the toolbar items for a
     * particular toolbar group.
     *
     * @param array
     */
    public function getToolbarOrder($group) {
        return $this->toolbarOrders[$group];
    }

    /**
     * Sets the toolbar items for a
     * particular toolbar group.
     *
     * @param string $group
     * @param array $items
     * @return void
     */
    public function setToolbarOrder($group, $items) {
        $this->toolbarOrders[$group] = $items;
    }

    /**
     * Sets the toolbar items for all toolbar groups.
     *
     * @param array $items
     * @return void
     */
    public function setToolbarOrders(array $items) {
        $this->toolbarOrders = $items;
    }

    /**
     * Gets all the toolbar items.
     *
     * @return array
     */
    public function getToolbarOrders() {
        return $this->toolbarOrders;
    }

    /**
     * Get an toolbar item by its name.
     *
     * @param string $name
     * @return ToolbarItem
     */
    public function getToolbarItem($name) {
        if(!isset($this->toolbarItems[$name])) {
            return $this->toolbarItems[$this->getRouteName() . '.' . $name];
        }

        return $this->toolbarItems[$name];
    }

    /**
     * Get all the toolbar items.
     *
     * @return array
     */
    public function getToolbarItems() {
        return $this->toolbarItems;
    }

    /**
     * Add an ToolbarItem.
     *
     * @param ToolbarItem $item
     * @return void
     */
    public function addToolbarItem(ToolbarItem $item) {
        $this->toolbarItems[$item->getIdentifier()] = $item;
    }

    /**
     * Makes a new toolbar item and adds it to the Blueprint.
     *
     * @param array $parameters
     * @param FactoryInterface $factory Optional FactoryInterface
     * @return Action
     */
    public function makeToolbarItem(array $parameters, FactoryInterface $factory = null) {
        if($factory === null) {
            $factory = $this->getDefaultToolbarItemFactory();
        }

        if(is_string($parameters['action'])) {
            $parameters['action'] = $this->getAction($parameters['action']);
        }

        $item = $factory->create($parameters);

        $this->addToolbarItem($item);

        return $item;
    }

    /**
     * Remove a toolbar item.
     *
     * @param string $name
     * @return void
     */
    public function removeToolbarItem($name) {
        if(!isset($this->toolbarItems[$name])) {
            unset($this->toolbarItems[$this->getRouteName() . '.' . $name]);
        }

        unset($this->toolbarItems[$name]);
    }

    /**
     * Sets the default FactoryInterface
     * to be used when creating toolbar items.
     *
     * @param FactoryInterface $factory
     * @return void
     */
    public function setDefaultToolbarItemFactory(FactoryInterface $factory) {
        $this->defaultToolbarItemFactory = $factory;
    }

    /**
     * Returns the default FactoryInterface
     * to be used when creating toolbar items.
     *
     * @return FactoryInterface
     */
    public function getDefaultToolbarItemFactory() {
        return $this->defaultToolbarItemFactory;
    }

    /**
     * Uses a pre-configured trait.
     *
     * @param BlueprintTraitInterface $trait the trait instance
     * @return void
     */
    public function useTrait(BlueprintTraitInterface $trait) {
        $trait->applyTrait($this);
    }

}
