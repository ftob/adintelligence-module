<?php
namespace AdIntelligence\Client\Tests\Services;

use AdIntelligence\Client\Models\Contracts\TaskInterface;
use AdIntelligence\Client\Services\ClientService;
use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use AdIntelligence\Client\Services\Contracts\RequesterInterface;
use AdIntelligence\Client\Tests\CreateApplication;
use AdIntelligence\Client\Repositories\EloquentRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Class ClientServiceTest
 * @package AdIntelligence\Client\Tests\Services
 */
class ClientServiceTest extends TestCase {

    use CreateApplication;

    /** @var  ClientInterface */
    protected $client;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem  */
    protected $storage;

    /** @var RepositoryInterface */
    protected $repository;

    public function setUp()
    {

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar']),
            new Response(202, ['Content-Length' => 0]),
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $this->storage = \Mockery::mock('Illuminate\Contracts\Filesystem\Filesystem');
        $this->storage->shouldReceive('put')->withAnyArgs()->andReturn(true);
        $taskMock = \Mockery::mock(TaskInterface::class);
        $taskMock->shouldReceive('save')->andReturn(true);
        $this->repository = new EloquentRepository($taskMock);
    }


    public function testRun() {
        /** @var RequesterInterface $service */
        $service = new ClientService(
            $this->client,
            $this->storage,
            $this->repository
        );
        $uri = new Uri("http://google.com");
        $repository = $service->get($uri);

        $this->assertInstanceOf(RepositoryInterface::class, $repository);
        $this->assertEquals(RepositoryInterface::DONE, $service->status());
    }
}