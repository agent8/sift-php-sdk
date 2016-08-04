<?php

namespace spec\Easilydo\EmailConnections;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class YahooConnectionSpec extends ObjectBehavior
{
    function it_returns_an_add_body_containing_account_refresh_token_and_redirect_uri()
    {
        $this->beConstructedWith('test@email.com', 'abc123', 'http://www.google.com/');
        $this->getAddBody()->shouldBeLike([
            'account_type' => 'yahoo',
            'account' => 'test@email.com',
            'refresh_token' => 'abc123',
            'redirect_uri' => 'http://www.google.com/'
        ]);
    }
}
