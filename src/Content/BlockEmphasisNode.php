<?php

namespace Oxygen\Core\Content;

use     DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class BlockEmphasisNode extends Node {

    public static $name = 'blockEmphasis';

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
                'tag' => 'div',
                'getAttrs' => function(DOMElement $DOMNode) {
                    $classList = explode(' ', $DOMNode->getAttribute('class'));
                    return in_array('BlockEmphasis', $classList);
                }
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        return ['div', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes, ['class' => 'BlockEmphasis']), 0];
    }

}