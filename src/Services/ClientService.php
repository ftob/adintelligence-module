<?php
namespace AdIntelligence\Client\Services;


use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use AdIntelligence\Client\Services\Contracts\RequesterInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
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

    /** @var  string */
    protected $content;

    /** @var  UriInterface */
    private $uri;

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
        $promise = $this->client->requestAsync('GET', $uri, ['verify' => false, 'stream' => true]);

        // Pending stage
        if($promise->getState() === PromiseInterface::PENDING) {

            $this->onPending();
        }

        $promise->then(
            // Success
            function (ResponseInterface $res) {
                $body = $res->getBody();
                // Downloading
                while (!$body->eof()) {

                    if($this->status === RepositoryInterface::PENDING) {
                        $this->onDownload();
                    }
                    $this->putContent($body->read(1024));
                }
                $this->onDone();
            },
            // Reject status
            function (RequestException $e) {
                $this->onReject($e->getMessage());
            }
        )->wait();

        return $this->repository;
    }

    /**
     * @param $content
     */
    protected function putContent($content)
    {
        $this->content = $content;
        $this->storage->put('/tmp/' . sha1(md5($this->uri)), $this->content);
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->repository->getStatus();
    }

    /**
     * @return bool
     */
    private function onDownload():bool
    {
        $this->status = RepositoryInterface::DOWNLOADING;
        $this->afterOnDownload();
        return $this->repository->changeStatus(RepositoryInterface::DOWNLOADING);
    }

    /**
     * @return bool
     */
    private function onPending():bool
    {
        $this->status = RepositoryInterface::PENDING;
        $this->afterOnPending();
        return $this->repository->changeStatus(RepositoryInterface::PENDING);

    }

    /**
     * @return bool
     */
    private function onDone():bool
    {
        $this->status = RepositoryInterface::DONE;
        $this->afterOnDone();
        return $this->repository->changeStatus(RepositoryInterface::DONE);
    }

    /**
     * @param string $message
     * @return bool
     */
    private function onReject($message = ''):bool
    {
        $this->status = RepositoryInterface::ERROR;
        $this->afterOnReject();
        return $this->repository->changeStatus(RepositoryInterface::ERROR, $message);
    }

    protected function afterOnDownload()
    {
        // Event downloading
    }

    protected function afterOnPending()
    {
        // Event pending
    }

    protected function afterOnDone()
    {
        // Event done
    }

    protected function afterOnReject()
    {
        // Event reject
    }
}