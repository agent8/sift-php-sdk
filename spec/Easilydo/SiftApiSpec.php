<?php

namespace spec\Easilydo;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Easilydo\SiftApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class SiftApiSpec extends ObjectBehavior
{
    function it_throws_an_exception_when_api_key_null()
    {
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', [NULL, 'abc123']);
    }

    function it_throws_an_exception_when_api_key_empty()
    {
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', ['', 'abc123']);
    }

    function it_throws_an_exception_when_api_secret_null()
    {
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', ['abc123', NULL]);
    }

    function it_throws_an_exception_when_api_secret_empty()
    {
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', ['abc123', '']);
    }

    function it_throws_an_exception_when_guzzle_client_is_not_instance_of_guzzlehttp_client()
    {
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', ['abc123', 'def456', 'ghi7']);
    }

    function it_throws_an_exception_when_guzzle_client_is_guzzlehttp_client_with_incorrect_base_uri()
    {
        $client = new Client();
        $this->shouldThrow('Easilydo\Exceptions\SiftApiException')->during('__construct', ['abc', 'def', $client]);
    }

    /**
     * Client initializers for API tests
     */
    private function init_response_client($mock)
    {
        $handler = HandlerStack::create($mock);
        return new Client(['base_uri' => SiftApi::API_URL, 'handler' => $handler]);
    }

    private function init_400_response_client()
    {
        $mock = new MockHandler([new Response(400, ['Content-Length' => 0])]);
        return $this->init_response_client($mock);
    }

    private function init_exception_response_client($method, $path)
    {
        $mock = new MockHandler([new RequestException("Error Communicating with Server", new Request($method, $path))]);
        return $this->init_response_client($mock);
    }

    private function init_body_400_response_client()
    {
        $mock = new MockHandler([new Response(200, [], '{"code": 400, "message": "Bad Request"}')]);
        return $this->init_response_client($mock);
    }

    private function init_success_response_client($body)
    {
        $mock = new MockHandler([new Response(200, [], $body)]);
        return $this->init_response_client($mock);
    }

    /**
     * API tests
     */
    function it_throws_an_exception_when_posting_discovery_and_request_returns_400()
    {
        $client = $this->init_400_response_client();
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDiscovery('test eml file');
    }

    function it_throws_an_exception_when_posting_discovery_and_request_exception_occurs()
    {
        $client = $this->init_exception_response_client('POST', '/v1/discovery');
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDiscovery('test eml file');
    }

    function it_throws_an_exception_when_posting_discovery_and_body_code_is_not_200()
    {
        $client = $this->init_body_400_response_client();
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDiscovery('test eml file');
    }

    function it_returns_the_json_decoded_body_when_posting_discovery_and_successful()
    {
        $body = '{"code": 200, "message": "Success", "result": {}}';
        $client = $this->init_success_response_client($body);
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->discovery('test eml file')->shouldBeLike(json_decode($body, true));
    }

    function it_throws_an_exception_when_deleting_user_and_request_returns_400()
    {
        $client = $this->init_400_response_client();
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDeleteUser('testuser');
    }

    function it_throws_an_exception_when_deleting_user_and_request_exception_occurs()
    {
        $client = $this->init_exception_response_client('POST', '/v1/discovery');
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDeleteUser('testuser');
    }

    function it_throws_an_exception_when_deleting_user_and_body_code_is_not_200()
    {
        $client = $this->init_body_400_response_client();
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->shouldThrow('Easilydo\Exceptions\SiftRequestException')->duringDeleteUser('testuser');
    }

    function it_returns_the_json_decoded_body_when_deleting_user_and_successful()
    {
        $body = '{"code": 200, "message": "Success", "result": {}}';
        $client = $this->init_success_response_client($body);
        $this->beConstructedWith('abc123', 'def456', $client);

        $this->deleteUser('testuser')->shouldBeLike(json_decode($body, true));
    }
}
