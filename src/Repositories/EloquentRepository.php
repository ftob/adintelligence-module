<?php
namespace AdIntelligence\Repositories;

use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentRepository
 * @package AdIntelligence\Repositories
 */
class EloquentRepository implements RepositoryInterface
{
    /** @var  Model */
    protected $model;

    public function __construct(Model $model)
    {

    }

    public function changeStatus(int $status): bool
    {
        return true;
    }

}