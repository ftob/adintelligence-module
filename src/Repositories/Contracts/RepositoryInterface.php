<?php

namespace AdIntelligence\Client\Repositories\Contracts;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface {

    public const ERROR = -1, PENDING = 0, DOWNLOADING = 1, DONE = 2;

    public function changeStatus(int $status, $message = ''): bool;

    public function getStatus():int;
}