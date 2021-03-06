<?php

namespace Oxygen\Core\Action;

class Group {

    /**
     * URL Prefix for the group.
     * All actions using this group will have the prefix prepended to their pattern.
     * For example, an Action with a pattern of `my-pattern` and a group with a prefix
     * of `my-prefix` will result in the pattern `my-prefix/my-pattern`.
     *
     * @var string
     */

    protected $pattern;

    /**
     * Name of the group, in camelCase. Used in permissions strings.
     * Often based upon the `routeName` of the parent Blueprint For example,
     * the $groupName of  Blueprint called `MyResource` would be `myResources`.
     *
     * @var string
     */

    protected $name;

    /**
     * Constructs the Group.
     *
     * @param string $name
     * @param string $pattern
     */
    public function __construct($name = null, $pattern = null) {
        $this->name = $name;
        $this->pattern = $pattern;
    }

    /**
     * Returns the pattern of the group.
     *
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Determines whether the group has a pattern.
     *
     * @return boolean
     */
    public function hasPattern() {
        return $this->pattern !== null;
    }

    /**
     * Returns the name of the group.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Determines whether the group has a name.
     *
     * @return boolean
     */
    public function hasName() {
        return $this->name !== null;
    }

}
