<?php

namespace Oxygen\Core\Content;

use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class ObjectLinkMark extends Node {
    public static $name = 'objectLink';

    private ObjectLinkRegistry $registry;

    public function __construct(ObjectLinkRegistry $registry) {
        $this->registry = $registry;
    }

    public function addOptions(): array {
        return [
            'HTMLAttributes' => [
                'target' => '_blank',
                'rel' => 'noopener noreferrer nofollow',
            ],
        ];
    }

    /**
     * different types of object links can appear as different types of content in the HTML.
     * @return array|string[]
     */
    public function parseHTML() {
        return array_merge($this->registry->getParseConfig(), [
            'tag' => 'object-link'
        ]);
    }

    private function getParseAttrFunc(string $attr) {
        return function(\DOMElement $DOMNode) use ($attr) {
            foreach($this->registry->types as $type) {
                $attrs = $type->parse($DOMNode);
                if($attrs !== null) {
                    return isset($attrs[$attr]) ? $attrs[$attr] : null;
                }
            }
            return null;
        };
    }

    public function addAttributes()
    {
        return [
            'type' => [
                'parseHTML' => $this->getParseAttrFunc('type')
            ],
            'id' => [
                'parseHTML' => $this->getParseAttrFunc('id')
            ],
            'content' => [
                'parseHTML' => $this->getParseAttrFunc('content')
            ],
            'target' => [],
            'rel' => [],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = [])
    {
        $href = $this->registry->resolve($HTMLAttributes['type'], intval($HTMLAttributes['id']));
        unset($HTMLAttributes['type']);
        unset($HTMLAttributes['id']);
        return [
            'a',
            HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes, ['href' => $href]),
            0,
        ];
    }

}