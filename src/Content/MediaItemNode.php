<?php

namespace Oxygen\Core\Content;

use DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class MediaItemNode extends Node {

    public static $name = 'mediaItem';

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
                    return intval($DOMNode->getAttribute('id'));
                },
            ],
            'target' => [

            ],
            'type' => [

            ],
            'size' => []
        ];
    }

    public function parseHTML()
    {
        return [
            [
                'tag' => 'media-item'
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        return ['media-item', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }


}