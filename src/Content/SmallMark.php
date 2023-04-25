<?php

namespace Oxygen\Core\Content;

use Tiptap\Core\Mark;
use Tiptap\Utils\HTML;

class SmallMark extends Mark {

    public static $name = 'small';

    public function addOptions()
    {
        return [
            'HTMLAttributes' => []
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
                'tag' => 'small'
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = [])
    {
        return ['small', HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes), 0];
    }

}