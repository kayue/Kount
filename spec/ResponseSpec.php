<?php

namespace spec\Kayue\Kount;

use GuzzleHttp\Psr7;
use Kayue\Kount\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin Response
 */
class ResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Kayue\Kount\Response');
    }

    function let(Psr7\Response $response)
    {
        $this->beConstructedWith($response);
    }

    function it_return_ok_status(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getGoodKountStream());

        $this->getStatus()->shouldReturn('ok');
    }

    function it_return_failure_status(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getBadKountStream());

        $this->getStatus()->shouldReturn('failure');
    }

    function it_return_errors(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getBadKountStream());

        $this->getErrors()->shouldHaveCount(1);
    }

    function it_return_no_errors(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getGoodKountStream());

        $this->getErrors()->shouldHaveCount(0);
    }

    function it_return_result(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getGoodKountStream());

        $this->getResult()->shouldReturn('decline');
    }

    function it_return_no_result(Psr7\Response $response)
    {
        $response->getBody()->willReturn($this->getBadKountStream());

        $this->getResult()->shouldReturn(null);
    }

    private function getGoodKountStream()
    {
        return Psr7\stream_for('
        {
            "status": "ok",
            "count": {
                "success": 1,
                "failure": 0
            },
            "errors": [],
            "result": "decline"
        }
        ');
    }

    private function getBadKountStream()
    {
        return Psr7\stream_for('
        {
            "status": "failure",
            "count": {
                "success": 0,
                "failure": 1
            },
            "errors": [{
                "code": 1552,
                "msg": "Email address not found [kayue@example.com]",
                "scope": "kayue@example.com",
                "type": 404
            }]
        }
        ');
    }
}
