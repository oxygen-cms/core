<?php

namespace spec\Oxygen\Core\Blueprint;

use Oxygen\Core\Action\Action;
use Oxygen\Core\Html\Toolbar\ToolbarItem;
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
        $this->getPluralName()->shouldReturn('TestBlueprints');
        $this->getDisplayName()->shouldReturn('Test Blueprint');
        $this->getPluralDisplayName()->shouldReturn('Test Blueprints');
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

    function it_stores_actions(Action $action) {
        $action->name = 'testAction';
        $this->addAction($action);
        $this->getAction('testAction')->shouldReturn($action);
    }

    function it_stores_toolbar_items(ToolbarItem $item) {
        $item->getIdentifier()->willReturn('test-identifier');
        $this->addToolbarItem($item);
        $this->getToolbarItem('test-identifier')->shouldReturn($item);
        $this->removeToolbarItem('test-identifier');
    }

    function it_can_make_toolbar_items(Action $action) {
        $action->name = 'testAction';
        $this->addAction($action);

        $this->makeToolbarItem([
            'action' => 'testAction',
            'identifier' => 'test-identifier',
            'label' => 'Foo'
        ]);
        $this->getToolbarItem('test-identifier')->shouldBeAnInstanceOf('Oxygen\Core\Html\Toolbar\ToolbarItem');
    }

}
