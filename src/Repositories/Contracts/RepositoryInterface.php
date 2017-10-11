<?php

namespace AdIntelligence\Client\Repositories\Contracts;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{

    const ERROR = -1;
    const PENDING = 0;
    const DOWNLOADING = 1;
    const DONE = 2;

    public function changeStatus(int $status, $message = ''): bool;

    public function getStatus():int;

    public function all();
}