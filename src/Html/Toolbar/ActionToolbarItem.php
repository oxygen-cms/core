<?php

namespace Oxygen\Core\Html\Toolbar;

use Closure;
use Exception;

use Auth;
use URL;
use Request;

use Oxygen\Core\Action\Action;

abstract class ActionToolbarItem implements ToolbarItem {

    /**
     * The parent action.
     *
     * @var \Oxygen\Core\Action\Action
     */

    public $action;

    /**
     * A custom identifier that will override the action's name.
     *
     * @var string
     */

    public $identifier;

    /**
     * Callback function to determine if the toolbar item should render
     *
     * @var Closure
     */

    public $shouldRenderCallback;

    /**
     * Callback functions that will customize the toolbar item at runtime.
     *
     * @var array
     */

    public $dynamicCallbacks;

    /**
     * Constructs the ToolbarItem
     *
     * @param \Oxygen\Core\Action\Action $action
     */

    public function __construct(Action $action) {
        $this->action = $action;
        $this->identifier = null;
        $this->shouldRenderCallback = function(ActionToolbarItem $item, array $arguments) {
            return $item->shouldRenderBasic($arguments);
        };
        $this->dynamicCallbacks = [];
    }

    /**
     * Returns a unique identifier for the ToolbarItem
     *
     * @return string
     */

    public function getIdentifier() {
        return $this->identifier === null ? $this->action->getName() : $this->identifier;
    }

    /**
     * Determines if the button should be rendered.
     *
     * @param array $arguments
     * @return boolean
     */

    public function shouldRender(array $arguments = []) {
        $callback = $this->shouldRenderCallback;
        $result = $callback($this, $arguments);
        return $result;
    }

    /**
     * Adds a dynamic callback.
     *
     * @param Closure $callback
     * @return void
     */

    public function addDynamicCallback(Closure $callback) {
        $this->dynamicCallbacks[] = $callback;
        return $this;
    }

    /**
     * Runs dynamic callbacks.
     *
     * @param array $arguments
     * @return void
     */

    public function runDynamicCallbacks(array $arguments) {
        foreach($this->dynamicCallbacks as $callback) {
            $callback($this, $arguments);
        }
    }

    /**
     * Provides simple shouldRender check, that checks.
     *
     * @return boolean
     */

    public function shouldRenderBasic(array $arguments) {
        if(isset($arguments['evenOnSamePage']) && $arguments['evenOnSamePage'] === true) {
            return $this->hasPermissions();
        } else {
            return $this->hasPermissions() && !$this->linksToCurrentPage($arguments);
        }
    }

    /**
     * Determines if the user has the required permissions for the toolbar item.
     *
     * @return boolean
     */

    public function hasPermissions() {
        return $this->action->usesPermissions()
            ? Auth::user()->hasPermissions($this->action->getPermissions())
            : true;
    }

    /**
     * Determines if the ActionToolbarItem will link to the current page.
     *
     * @param array $arguments
     * @return boolean
     */

    public function linksToCurrentPage(array $arguments) {
        return
            URL::current() == $this->action->getURL($arguments) &&
            Request::method() == $this->action->getMethod();
    }

    /**
     * Throws an exception when trying to set a non-existent property.
     *
     * @param string $variable name of the variable
     * @param mixed $value value of the variable
     * @throws Exception
     */

    public function __set($variable, $value) {
        throw new Exception('Unknown key "' . $variable . '"');
    }

}
