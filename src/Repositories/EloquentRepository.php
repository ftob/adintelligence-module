<?php
namespace AdIntelligence\Client\Repositories;

use AdIntelligence\Client\Models\Contracts\TaskInterface;
use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;

/**
 * Class EloquentRepository
 * @package AdIntelligence\Repositories
 */
class EloquentRepository implements RepositoryInterface
{
    /** @var  TaskInterface */
    protected $model;

    /**
     * EloquentRepository constructor.
     * @param TaskInterface $model
     */
    public function __construct(TaskInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $status
     * @param string $message
     * @return bool
     */
    public function changeStatus(int $status, $message = ''): bool
    {
        $this->model->status = $status;
        $this->model->message = trim($status);
        return $this->model->save();
    }

    /**
     * @return int
     */
    public function getStatus():int
    {
        return $this->model->status;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function create($attribute)
    {
        return $this->model->fill($attribute)->save();
    }

}