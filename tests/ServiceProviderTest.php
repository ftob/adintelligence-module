<?php
namespace AdIntelligence\Client\Tests;
use AdIntelligence\Client\ServiceProvider;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProviderTest extends TestCase
{
    use CreateApplication;

    /**
     * @var \Mockery\Mock
     */
    protected $applicationMock;

    /**
     * @var ServiceProvider
     */
    protected $service_provider;

    protected function setUp()
    {
        $this->setUpMocks();

        $this->service_provider = new ServiceProvider($this->applicationMock);

        parent::setUp();
    }


    protected function setUpMocks()
    {
        $this->applicationMock = \Mockery::mock(Application::class);
    }
    /**
     * @test
     */
    public function it_can_be_constructed()
    {
        $this->assertInstanceOf(LaravelServiceProvider::class, $this->service_provider);
    }


    /**
     * @test
     */
    public function it_performs_a_boot_method()
    {
        $this->applicationMock->shouldReceive('mergeConfigFrom')
            ->withAnyArgs()
            ->andReturnNull();
        $this->applicationMock->shouldReceive('publishes')
            ->withAnyArgs()->andReturnNull();
        $this->service_provider->boot();

    }


}