<?php

namespace Theafolayan\ListmonkLaravel\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Theafolayan\ListmonkLaravel\Http\Client;
use Theafolayan\ListmonkLaravel\Listmonk;

class ListmonkTest extends TestCase
{
    /** @var array<int, array<string, mixed>> */
    protected $history = [];

    public function testTheClientAuthenticatesWithTheConfiguredCredentials()
    {
        $client = new class extends Client {
            public function guzzle(): GuzzleClient
            {
                return $this->client;
            }
        };

        $this->assertSame('https://listmonk.test', (string) $client->guzzle()->getConfig('base_uri'));
        $this->assertSame('token api-user:api-token', $client->guzzle()->getConfig('headers')['Authorization']);
    }

    public function testASubscriberIsCreated()
    {
        $listmonk = $this->listmonkReturning([new Response(200, [], '{"data":{"id":7}}')]);

        $response = $listmonk->createSubscriber(['email' => 'john.doe@example.com', 'name' => 'John Doe']);

        $this->assertSame(['data' => ['id' => 7]], $response);

        $request = $this->history[0]['request'];
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://listmonk.test/api/subscribers', (string) $request->getUri());
        $this->assertSame(
            ['email' => 'john.doe@example.com', 'name' => 'John Doe'],
            json_decode((string) $request->getBody(), true)
        );
    }

    public function testSubscribersAreFetched()
    {
        $listmonk = $this->listmonkReturning([new Response(200, [], '{"data":{"results":[]}}')]);

        $this->assertSame(['data' => ['results' => []]], $listmonk->getSubscribers());
        $this->assertSame('GET', $this->history[0]['request']->getMethod());
        $this->assertSame('/api/subscribers', $this->history[0]['request']->getUri()->getPath());
    }

    public function testListsAreFetchedAndCreated()
    {
        $listmonk = $this->listmonkReturning([
            new Response(200, [], '{"data":{"results":[]}}'),
            new Response(200, [], '{"data":{"id":3}}'),
        ]);

        $listmonk->getLists();
        $response = $listmonk->createList(['name' => 'Weekly Newsletter']);

        $this->assertSame(['data' => ['id' => 3]], $response);
        $this->assertSame('GET', $this->history[0]['request']->getMethod());
        $this->assertSame('/api/lists', $this->history[0]['request']->getUri()->getPath());
        $this->assertSame('POST', $this->history[1]['request']->getMethod());
        $this->assertSame(['name' => 'Weekly Newsletter'], json_decode((string) $this->history[1]['request']->getBody(), true));
    }

    /**
     * A Listmonk service whose HTTP client answers from a queue instead of the network.
     *
     * @param  array<int, Response>  $responses
     */
    protected function listmonkReturning(array $responses): Listmonk
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($this->history));

        $guzzle = new GuzzleClient(['base_uri' => config('listmonk.base_url'), 'handler' => $stack]);

        $client = new class($guzzle) extends Client {
            public function __construct(GuzzleClient $guzzle)
            {
                $this->client = $guzzle;
            }
        };

        return new class($client) extends Listmonk {
            public function __construct(Client $client)
            {
                $this->client = $client;
            }
        };
    }
}
