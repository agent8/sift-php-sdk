<?php

namespace spec\Easilydo\EmailConnections;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExchangeConnectionSpec extends ObjectBehavior
{
    function it_returns_an_add_body_containing_email_and_password()
    {
        $this->beConstructedWith('test@email.com', 'abc123');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'exchange',
            'email' => 'test@email.com',
            'password' => 'abc123'
        ]);
    }

    function it_returns_an_add_body_containing_account()
    {
        $this->beConstructedWith('test@email.com', 'abc123', 'account456');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'exchange',
            'email' => 'test@email.com',
            'password' => 'abc123',
            'account' => 'account456'
        ]);
    }

    function it_returns_an_add_body_containing_host()
    {
        $this->beConstructedWith('test@email.com', 'abc123', NULL, 'host789');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'exchange',
            'email' => 'test@email.com',
            'password' => 'abc123',
            'host' => 'host789'
        ]);
    }

    function it_returns_an_add_body_containing_account_and_host()
    {
        $this->beConstructedWith('test@email.com', 'abc123', 'account456', 'host789');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'exchange',
            'email' => 'test@email.com',
            'password' => 'abc123',
            'account' => 'account456',
            'host' => 'host789'
        ]);
    }
}
