<?php

namespace spec\Oxygen\Core\Action;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdminActionSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('testAction', 'test-action', 'TestActionController@foo');
    }

    function it_is_initializable() {
        $this->shouldHaveType('Oxygen\Core\Action\AdminAction');
    }

    function it_automatically_uses_the_auth_and_permissions_filters() {
        $this->getMiddleware()->shouldReturn(['web', 'oxygen.auth', '2fa.require', 'oxygen.permissions:testAction']);
    }

}
