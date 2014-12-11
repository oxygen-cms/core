<?php

namespace spec\Oxygen\Core\Theme;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThemeSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('testTheme');
    }

    function it_is_initializable() {
        $this->shouldHaveType('Oxygen\Core\Theme\Theme');
    }

    function it_has_a_key() {
        $this->getKey()->shouldReturn('testTheme');
    }

    function it_has_a_name() {
        $this->setName('Test Theme');
        $this->getName()->shouldReturn('Test Theme');
    }

    function it_can_provide_things() {
        $this->getProvides()->shouldHaveCount(0);
        $this->provides('foo', 'bar');
        $this->getProvides()->shouldBe(['foo' => 'bar']);
    }

    function it_can_have_an_image() {
        $this->hasImage()->shouldReturn(false);
        $this->setImage('image');
        $this->getImage()->shouldReturn('image');
        $this->hasImage()->shouldReturn(true);
    }

    function it_can_be_filled_from_an_array() {
        $this->fillFromArray([
            'name' => 'Other Theme',
            'provides' => [
                'other' => 'option'
            ]
        ]);
        $this->getName()->shouldReturn('Other Theme');
        $this->getProvides()->shouldReturn(['other' => 'option']);
    }

}
