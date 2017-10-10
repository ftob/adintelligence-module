<?php
namespace AdIntelligence\Models;

use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['url', 'path', 'status'];
    protected $timestamp = true;

    /**
     * @param $value
     */
    public function setStatusAttribute($value)
    {
        if (in_array($value, [
            RepositoryInterface::DONE,
            RepositoryInterface::ERROR,
            RepositoryInterface::PENDING,
            RepositoryInterface::DOWNLOADING,
        ])) {
            $this->attributes['status'] = $value;
        }
    }
}