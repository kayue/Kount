<?php

namespace spec\Kayue\Kount;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KountSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kayue\Kount\Kount');
    }

    function let()
    {
        $this->beConstructedWith('API_KEY');
    }

    function it_should_return_api_key()
    {
        $this->getApiKey()->shouldReturn('API_KEY');
    }
}
