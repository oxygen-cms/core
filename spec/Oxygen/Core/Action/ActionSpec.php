<?php

namespace spec\Oxygen\Core\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('testAction', 'test-action', 'TestActionController@foo');
    }

    function it_is_initializable() {
        $this->shouldHaveType('Oxygen\Core\Action\Action');
    }

    function it_has_a_name() {
        $this->getName()->shouldReturn('testAction');
    }

    function it_has_a_pattern() {
        $this->getPattern()->shouldReturn('test-action');
    }

}
