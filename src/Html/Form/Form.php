<?php

namespace Oxygen\Core\Html\Form;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Http\Method;

class Form implements RenderableInterface {

    use RenderableTrait;

    /**
     * An array of form rows
     *
     * @var array
     */
    protected $content;

    /**
     * The action for the form.
     *
     * @var Action
     */
    protected $action;

    /**
     * Should be true if the form facilitates file uploads
     *
     * @var bool
     */
    protected $useMultiPartFormData;

    /**
     * Should the form be submitted with AJAX?
     *
     * @var bool
     */
    protected $asynchronous;

    /**
     * Should the user be warned before changing page with the form unsaved?
     *
     * @var bool
     */
    protected $warnBeforeExit;

    /**
     * Should the form be submitted when the 'save' shortcut is invoked?
     *
     * @var bool
     */
    protected $submitOnShortcutKey;

    /**
     * Custom classes to add to the form.
     *
     * @var array
     */
    protected $extraClasses;

    /**
     * The arguments that will be passed to $action->getRouteParameters() later on
     *
     * @var array
     */
    protected $routeParameters;

    /**
     * Constructs the Form instance
     *
     * @param \Oxygen\Core\Action\Action $action         the Oxygen action
     */
    public function __construct(Action $action) {
        $this->action = $action;
    }

    /**
     * Sets the arguments that will be passed to $action->getRouteParameters() later on
     *
     * @param $arguments
     */
    public function setRouteParameters($arguments) {
        $this->routeParameters = $arguments;
    }

    /**
     * @return array
     */
    public function getRouteParameters() {
        return $this->routeParameters;
    }

    /**
     * Adds a single row to the form.
     *
     * @param \Oxygen\Core\Html\RenderableInterface|string $row
     * @return $this
     */
    public function addContent($row) {
        $this->content[] = $row;
        return $this;
    }

    /**
     * Returns the action of the form.
     *
     * @return Action
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @return boolean
     */
    public function useMultiPartFormData() {
        return $this->useMultiPartFormData;
    }

    /**
     * @param boolean $useMultiPartFormData
     * @return $this
     */
    public function setUseMultiPartFormData($useMultiPartFormData) {
        $this->useMultiPartFormData = $useMultiPartFormData;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAsynchronous() {
        return $this->asynchronous;
    }

    /**
     * @param boolean $asynchronous
     * @return $this
     */
    public function setAsynchronous($asynchronous) {
        $this->asynchronous = $asynchronous;
        return $this;
    }

    /**
     * @return boolean
     */
    public function shouldWarnBeforeExit() {
        return $this->warnBeforeExit;
    }

    /**
     * @param boolean $warnBeforeExit
     * @return $this
     */
    public function setWarnBeforeExit($warnBeforeExit) {
        $this->warnBeforeExit = $warnBeforeExit;
        return $this;
    }

    /**
     * @return boolean
     */
    public function shouldSubmitOnShortcutKey() {
        return $this->submitOnShortcutKey;
    }

    /**
     * @param boolean $submitOnShortcutKey
     * @return $this
     */
    public function setSubmitOnShortcutKey($submitOnShortcutKey) {
        $this->submitOnShortcutKey = $submitOnShortcutKey;
        return $this;
    }

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
