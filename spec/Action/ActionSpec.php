<?php

namespace spec\Oxygen\Core\Action;

use Oxygen\Core\Action\Group;
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

    function it_has_a_method() {
        // must return uppercase
        $this->getMethod()->shouldReturn('GET');
        $this->method = 'post';
        $this->getMethod()->shouldReturn('POST');
    }

    function it_has_a_uses_property() {
        $this->getUses()->shouldReturn('TestActionController@foo');
    }

    function it_can_be_in_a_group(Group $group) {
        $group->hasName()->willReturn(true);
        $group->hasPattern()->willReturn(true);
        $group->getName()->willReturn('groupName');
        $group->getPattern()->willReturn('group-pattern');
        $this->group = $group;

        $this->getName()->shouldReturn('groupName.testAction');
        $this->getPattern()->shouldReturn('group-pattern/test-action');
    }

    function it_has_middleware() {
        $this->getMiddleware()->shouldReturn([]);

        $this->permissions = 'my.permissions';
        $this->getMiddleware()->shouldReturn(['oxygen.permissions:my.permissions']);
    }

    function it_has_route_parameters() {
        $this->getRouteParameters([])->shouldReturn([]);
    }

}
