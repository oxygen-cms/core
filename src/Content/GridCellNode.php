<?php

namespace Oxygen\Core\Content;

use DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class GridCellNode extends Node {

    public static $name = 'gridCell';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function addAttributes()
    {
        return [
            'cellType' => [
                'default' => 'wide',
                'parseHTML' => function(DOMElement $DOMNode) {
                    $classList = explode(' ', $DOMNode->getAttribute('class'));
                    return in_array('Cell--narrow', $classList) ? 'narrow' : 'wide';
                }
            ],
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'div',
                'getAttrs' => function(DOMElement $DOMNode) {
                    $classList = explode(' ', $DOMNode->getAttribute('class'));
                    return in_array('Cell', $classList);
                }
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        return ['div', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }


}