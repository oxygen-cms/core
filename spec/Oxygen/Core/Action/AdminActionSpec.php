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
        $this->getBeforeFilters()->shouldReturn(['oxygen.auth', 'oxygen.permissions:testAction']);
    }

    function it_uses_the_csrf_filter_on_all_methods_except_get() {
        $this->method = 'POST';
        $this->getBeforeFilters()->shouldReturn(['oxygen.auth', 'oxygen.csrf', 'oxygen.permissions:testAction']);
    }

}
