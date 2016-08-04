<?php

namespace spec\Easilydo\EmailConnections;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImapConnectionSpec extends ObjectBehavior
{
    function it_returns_an_add_body_containing_email_password_and_host()
    {
        $this->beConstructedWith('test@email.com', 'abc123', 'host456');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'imap',
            'account' => 'test@email.com',
            'password' => 'abc123',
            'host' => 'host456'
        ]);
    }
}
