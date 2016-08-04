<?php

namespace spec\Easilydo\EmailConnections;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleConnectionSpec extends ObjectBehavior
{
    function it_returns_an_add_body_containing_account_and_refresh_token()
    {
        $this->beConstructedWith('test@email.com', 'abc123');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'google',
            'account' => 'test@email.com',
            'refresh_token' => 'abc123'
        ]);
    }
}
