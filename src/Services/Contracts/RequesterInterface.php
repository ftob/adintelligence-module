<?php

namespace AdIntelligence\Client\Services\Contracts;


use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use Psr\Http\Message\UriInterface;

interface RequesterInterface {
    public function get(UriInterface $uri): RepositoryInterface;

    public function status();
}