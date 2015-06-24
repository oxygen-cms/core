<?php

namespace Oxygen\Core\Html\Header;

use Oxygen\Core\Form\FieldSet;
use Oxygen\Core\Html\RenderableInterface;
use Oxygen\Core\Model\Model;
use Oxygen\Core\Blueprint\Blueprint;
use Oxygen\Core\Html\RenderableTrait;
use Oxygen\Core\Html\Toolbar\Toolbar;

class Header implements RenderableInterface {

    use RenderableTrait;

    const TYPE_TINY    = 3;
    const TYPE_SMALL   = 0;
    const TYPE_NORMAL  = 1;
    const TYPE_MAIN    = 2;
    const TYPE_BLOCK   = 4;

    /**
     * Title of the section header.
     *
     * @var string
     */

    protected $title;

    /**
     * Title of the section header.
     *
     * @var string
     */

    protected $subtitle;

    /**
     * Icon of the section header.
     *
     * @var string
     */

    protected $icon;

    /**
     * Toolbar instance containing ToolbarItems
     *
     * @var Toolbar
     */

    protected $toolbar;

    /**
     * The arguments to be passed to the toolbar items.
     *
     * @var array
     */

    protected $arguments;

    /**
     * Type of the header.
     *
     * @var integer
     */

    protected $type;

    /**
     * Back button link, if any.
     *
     * @var string
     */

    protected $backLink;

    /**
     * Content
     *
     * @var string
     */

    protected $content;

    /**
     * Classes to be applied to the Header.
     *
     * @var array
     */

    protected $classes;

    /**
     * Unique index for the header.
     *
     * @var string|int
     */

    protected $index;

    /**
     * Constructs the Header.
     *
     * @param string  $title
     * @param array   $arguments
     * @param integer $type
     */
    public function __construct($title, array $arguments = [], $type = self::TYPE_MAIN) {
        $this->title         = $title;
        $this->subtitle      = null;
        $this->toolbar       = new Toolbar();
        $this->arguments     = $arguments;
        $this->type          = $type;
        $this->backLink      = null;
        $this->classes       = [];
    }

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Determines if the subtitle exists.
     *
     * @return boolean
     */
    public function hasSubtitle() {
        return $this->subtitle !== null;
    }

    /**
     * Returns the subtitle.
     *
     * @return string
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle.
     *
     * @param string $subtitle
     * @return void
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;
    }

    /**
     * Determines if the icon exists.
     *
     * @return boolean
     */
    public function hasIcon() {
        return $this->icon !== null;
    }

    /**
     * Returns the icon.
     *
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Sets the icon.
     *
     * @param string $icon
     * @return void
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    /**
     * Returns the toolbar.
     *
     * @return Toolbar
     */
    public function getToolbar() {
        return $this->toolbar;
    }

    /**
     * Returns the arguments.
     *
     * @return array
     */
    public function getArguments() {
        return $this->arguments;
    }

    /**
     * Returns the type of header.
     *
     * @return integer
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Sets the back link to the provided URL.
     *
     * @param string $url
     */
    public function setBackLink($url) {
        $this->backLink = $url;
    }

    /**
     * Returns the back link URL.
     *
     * @return string
     */
    public function getBackLink() {
        return $this->backLink;
    }

    /**
     * Sets the content
     *
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Returns the content.
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Determines if there is any content
     *
     * @return boolean
     */
    public function hasContent() {
        return $this->content !== null;
    }

    /**
     * Adds a class
     *
     * @param string $class
     */
    public function addClass($class) {
        $this->classes[] = $class;
    }

    /**
     * Returns the classes
     *
     * @return array
     */
    public function getClasses() {
        return $this->classes;
    }

    /**
     * Sets the index
     *
     * @param string|int $index
     * @return void
     */
    public function setIndex($index) {
        $this->index = $index;
    }

    /**
     * Returns the index
     *
     * @return string|int
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * Constructs a Header from a Blueprint.
     *
     * @param Blueprint $blueprint
     * @param FieldSet|string $title
     * @param array $arguments
     * @param integer $type
     * @param string $fillFromToolbar
     * @return Header
     */
    public static function fromBlueprint(Blueprint $blueprint, $title, array $arguments = [], $type = self::TYPE_MAIN, $fillFromToolbar = 'section') {
        if($title instanceof FieldSet) {
            $title = $arguments['model']->getAttribute($title->getTitleField());
        } else if(!is_string($title)) {
            throw new \InvalidArgumentException('$title must be either a string or an instance of \Oxygen\Core\Form\FieldSet');
        }

        $object = new static(
            $title,
            $arguments,
            $type
        );

        // sets a prefix to all the routes in the toolbar
        $object->getToolbar()->setPrefix($blueprint->getRouteName());

        // fills the toolbar with buttons from the blueprint
        $object->getToolbar()->fillFromBlueprint($blueprint, $fillFromToolbar);

        return $object;
    }

}
