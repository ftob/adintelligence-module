<?php
namespace AdIntelligence\Client\Services;


use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use AdIntelligence\Client\Services\Contracts\RequesterInterface;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Http\Message\UriInterface;

/**
 * Class ClientService
 * @package AdIntelligence\Client\Services
 */
class ClientService implements RequesterInterface
{

    /** @var ClientInterface  */
    protected $client;

    /** @var Filesystem  */
    protected $storage;

    /** @var RepositoryInterface  */
    protected $repository;

    /** @var  int */
    protected $status;

    /**
     * ClientService constructor.
     * @param ClientInterface $client
     * @param Filesystem $storage
     * @param RepositoryInterface $repository
     */
    public function __construct(ClientInterface $client, Filesystem $storage, RepositoryInterface $repository)
    {
        $this->client = $client;
        $this->storage = $storage;
        $this->repository = $repository;
    }

    public function get(UriInterface $uri): RepositoryInterface
    {
        //
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

}