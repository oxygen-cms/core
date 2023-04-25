<?php

namespace Oxygen\Core\Content;

use DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class GridRowNode extends Node {

    public static $name = 'gridRow';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function addAttributes()
    {
        return [
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'section',
                'getAttrs' => function(DOMElement $DOMNode) {
                    $classList = explode(' ', $DOMNode->getAttribute('class'));
                    return in_array('Row', $classList);
                }
            ],
            [
                'tag' => 'div',
                'getAttrs' => function(DOMElement $DOMNode) {
                    $classList = explode(' ', $DOMNode->getAttribute('class'));
                    return in_array('Row', $classList);
                }
            ]
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        return ['div', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes, ['class' => 'Row']), 0];
    }


}