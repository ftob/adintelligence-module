<?php
namespace AdIntelligence\Client\Models;

use AdIntelligence\Client\Models\Contracts\TaskInterface;
use AdIntelligence\Client\Models\Contracts\TaskTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @package AdIntelligence\Client\Models
 */
class Task extends Model implements TaskInterface
{
    use TaskTrait;

    protected $fillable = ['url', 'path', 'status'];
    protected $timestamp = true;
}