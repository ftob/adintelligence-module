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

    public function save(array $options = []);

    public static function all($columns = ['*']);

    public function fill(array $attributes);

}