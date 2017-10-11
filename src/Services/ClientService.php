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
    protected $uri;

    /** @var  string */
    protected $fullPath;


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
        $this->uri = $uri;
        $promise = $this->client->requestAsync('GET', $uri, ['verify' => false, 'stream' => true]);
        // Pending stage
        if($promise->getState() === PromiseInterface::PENDING) {
            $this->onPending();
        }
        $promise->then(
            // Success
            function (ResponseInterface $res) {
                $body = $res->getBody();
                $this->fullPath = $this->makeFullPath($this->uri, array_first($res->getHeader('Content-Type')));
                // Downloading
                while (!$body->eof()) {

                    if($this->status === RepositoryInterface::PENDING) {
                        $this->onDownload();
                    }
                    $this->putContent($body->read(65536), $this->fullPath);
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
     * @param $fullPath
     */
    protected function putContent($content, $fullPath)
    {
        $this->content = $content;
        $this->storage->append($fullPath, $this->content);
    }

    /**
     * @return int
     */
    public function status()
    {
        return $this->repository->getStatus($this->uri);
    }

    /**
     * @return bool
     */
    private function onDownload():bool
    {
        $this->status = RepositoryInterface::DOWNLOADING;
        $this->afterOnDownload();
        return $this->repository->changeStatus($this->uri,RepositoryInterface::DOWNLOADING);
    }

    /**
     * @return bool
     */
    private function onPending():bool
    {
        $this->status = RepositoryInterface::PENDING;
        $this->afterOnPending();
        return $this->repository->changeStatus($this->uri,RepositoryInterface::PENDING);

    }

    /**
     * @return bool
     */
    private function onDone():bool
    {
        $this->status = RepositoryInterface::DONE;
        $this->afterOnDone();
        return $this->repository->changeStatus($this->uri,RepositoryInterface::DONE);
    }

    /**
     * @param string $message
     * @return bool
     */
    private function onReject($message = ''):bool
    {
        $this->status = RepositoryInterface::ERROR;
        $this->afterOnReject();
        return $this->repository->changeStatus($this->uri, RepositoryInterface::ERROR, $message);
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

    private function getExtensionByContentType($contentType)
    {
        $map = array(
            'application/pdf'   => '.pdf',
            'application/zip'   => '.zip',
            'image/gif'         => '.gif',
            'image/jpeg'        => '.jpg',
            'image/png'         => '.png',
            'text/css'          => '.css',
            'text/html'         => '.html',
            'text/javascript'   => '.js',
            'text/plain'        => '.txt',
            'text/xml'          => '.xml',
        );
        if (isset($map[$contentType]))
        {
            return $map[$contentType];
        }

        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }

    // return /storage/tmp/(hash).(ext);(charset)
    private function makeFullPath($uri, $contentType):string
    {
        $storagePath = '';
        if (function_exists('config')) {
            try {
                $storagePath = config('adintelligence.storage_path');
            } catch (\ReflectionException $e) {
                //
            }
        }
        return $storagePath . sha1(md5($this->uri))
            . $this->getExtensionByContentType($contentType);
    }
}