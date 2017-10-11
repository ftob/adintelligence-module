<?php
namespace AdIntelligence\Client\Repositories\Contracts;

use Psr\Http\Message\UriInterface;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{

    const ERROR = -1;
    const PENDING = 0;
    const DOWNLOADING = 1;
    const DONE = 2;

    public function changeStatus(UriInterface $uri, int $status, $message = ''): bool;

    public function getStatus(UriInterface $uri):int;

    public function all();

    public function create(array $attributes);
}