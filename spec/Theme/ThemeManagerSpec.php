<?php

namespace spec\Oxygen\Core\Theme;

use Oxygen\Core\Theme\CurrentThemeLoader;
use Oxygen\Core\Theme\Theme;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThemeManagerSpec extends ObjectBehavior {

    function let(\Oxygen\Core\Theme\CurrentThemeLoader $loader) {
        $this->beConstructedWith($loader);
    }

    function it_is_initializable() {
        $this->shouldHaveType('Oxygen\Core\Theme\ThemeManager');
    }

    function it_can_store_themes(\Oxygen\Core\Theme\Theme $theme) {
        $theme->getKey()->willReturn('foo');
        $this->register($theme);
        $this->all()->shouldReturn(['foo' => $theme]);
    }

    function it_can_make_themes() {
        $this->make([
            'key' => 'fantasticTheme',
            'name' => 'FANTASTIC Theme',
            'provides' => [
                'foo' => 'bar'
            ]
        ]);
        $this->all()->shouldHaveCount(1);
    }

}
