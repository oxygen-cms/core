<?php

namespace spec\Oxygen\Core\Blueprint;

use Oxygen\Core\Blueprint\Blueprint;;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlueprintSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('TestBlueprint');
    }

    function it_is_initializable() {
        $this->shouldHaveType('Oxygen\Core\Blueprint\Blueprint');
    }

    function it_has_many_different_names() {
        $this->getName()->shouldReturn('TestBlueprint');
        $this->getDisplayName()->shouldReturn('Test Blueprint');
        $this->getDisplayName(Blueprint::PLURAL)->shouldReturn('Test Blueprints');
        $this->getRouteName()->shouldReturn('testBlueprints');
        $this->getRoutePattern()->shouldReturn('test-blueprints');
    }

    function it_stores_a_controller() {
        $this->setController('TestController');
        $this->getController()->shouldReturn('TestController');
    }

    function it_stores_an_icon() {
        $icon = 'test-icon';
        $this->setIcon($icon);
        $this->getIcon()->shouldReturn($icon);
    }

    function it_stores_toolbar_orders() {
        // bulk operation
        $orders = ['foo' => 'bar'];
        $this->setToolbarOrders($orders);
        $this->getToolbarOrders()->shouldReturn($orders);

        // single toolbar
        $this->setToolbarOrder('foo', 'baz');
        $this->getToolbarOrder('foo')->shouldReturn('baz');
    }

}
