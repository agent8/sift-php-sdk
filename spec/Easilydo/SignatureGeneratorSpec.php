<?php

namespace spec\Easilydo;

use Easilydo\SignatureGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SignatureGeneratorSpec extends ObjectBehavior
{
    function it_generates_a_sha1_hash()
    {
        $this::generate('secret', 'GET', '/v1/discovery')->shouldReturn('302d9e35db002bb2a29aac36efa7d34410d00c44');
    }

    function it_takes_params_into_account_in_hash()
    {
        $this::generate('secret', 'GET', '/v1/discovery', ['test' => '123'])
            ->shouldReturn('5807c9b51d2e809658dd1459e7fbdb409b9cac23');
    }

    function it_orders_params_alphabetically()
    {
        $this::generate('secret', 'GET', '/v1/discovery', ['test' => '123', 'abc' => 'def', 'zyx' => 'omg'])
            ->shouldReturn('8007b17837071d4a77c206b87280cc79bcd0488c');
    }
}
