<?php

namespace Oxygen\Core\Content;

use DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class PartialNode extends Node {

    public static $name = 'partial';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function addAttributes()
    {
        return [
            'id' => [
                'parseHTML' => function(DOMElement $DOMNode) {
                    return intval($DOMNode->getAttribute('data-partial-id'));
                },
            ],
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => function(DOMElement $DOMNode) {
                    return $DOMNode->hasAttribute('data-partial-id');
                }
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        return ['div', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }


}