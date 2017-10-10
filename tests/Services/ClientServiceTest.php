<?php
namespace AdIntelligence\Client\Tests\Services;


use AdIntelligence\Repositories\Contracts\RepositoryInterface;
use AdIntelligence\Services\Contracts\RequesterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\TestCase;

class ClientServiceTest extends TestCase {

    use \CreatesApplication;

    /** @var  ClientInterface */
    protected $client;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem  */
    protected $storage;

    /** @var RepositoryInterface */
    protected $entityStatus;

    public function setUp()
    {

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar']),
            new Response(202, ['Content-Length' => 0]),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
    }

    public function testRun() {
        /** @var RequesterInterface $service */
        $service = new \AdIntelligence\Client\Services\ClientService(
            $client,
            $storage,
            $entityStatus
        );
        $uri = new Uri("http://google.com");
        $service = $service->get($uri);

        $this->assertInternalType(RequesterInterface::class, $service);
        $this->assertEquals(RepositoryInterface::DONE, $service->status());

    }
}