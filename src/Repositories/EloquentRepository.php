<?php
namespace AdIntelligence\Client\Repositories;

use AdIntelligence\Client\Models\Contracts\TaskInterface;
use AdIntelligence\Client\Models\Task;
use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class EloquentRepository
 * @package AdIntelligence\Repositories
 */
class EloquentRepository implements RepositoryInterface
{
    /** @var Task */
    protected $model;

    /**
     * EloquentRepository constructor.
     * @param Task $model
     */
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * @param UriInterface $uri
     * @param int $status
     * @param string $message
     * @return bool
     */
    public function changeStatus(UriInterface $uri, int $status, $message = ''): bool
    {
        if(!$this->model->exists) {
            $this->model = $this->model->whereUrl($uri)->first();
        } else {
            $this->model = $this->create([
                'url' => $uri
            ]);
        }
        $this->model->status = $status;
        $this->model->message = trim($message);
        return $this->model->save();
    }

    /**
     * @param UriInterface $uri
     * @return int
     */
    public function getStatus(UriInterface $uri)
    {
        if(!$this->model->exists) {
            $this->model = $this->model->whereUrl($uri)->first();
        };
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
     * @return TaskInterface
     */
    public function create(array $attribute)
    {
        $this->model->fill($attribute)->save();
        return $this->model;
    }
}