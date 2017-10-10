<?php

namespace AdIntelligence\Services\Contracts;


use AdIntelligence\Repositories\Contracts\RepositoryInterface;
use Psr\Http\Message\UriInterface;

interface RequesterInterface {
    public function get(UriInterface $uri): RepositoryInterface;

    public function status(): int;
}