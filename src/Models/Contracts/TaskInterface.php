<?php
namespace AdIntelligence\Client\Models\Contracts;

/**
 * Interface TaskInterface
 * @package AdIntelligence\Client\Models\Contracts
 */
interface TaskInterface
{
    public function setStatusAttribute($value);

    public function setMessageAttribute($message);

    public function save($options = []):bool;

    public function all($columns = ['*']);
}