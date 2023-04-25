<?php

namespace Oxygen\Core\Content;

use DOMElement;
use Tiptap\Core\Node;
use Tiptap\Utils\HTML;

class RawHtmlNode extends Node {

    public static $name = 'rawHtml';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => [],
        ];
    }

    public function addAttributes()
    {
        return [
            'code' => [
                'parseHTML' => function(DOMElement $DOMNode) {
                    $html = $DOMNode->ownerDocument->saveHTML($DOMNode);
                    return $html;
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
                    return in_array('Slider', $classList) || in_array('Slidebox', $classList);
                }
            ],
        ];
    }

    public function renderHTML($node, $HTMLAttributes = [])
    {
        // let's turn code into raw html...
        return $HTMLAttributes['code'];
//        return ['div', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }

}