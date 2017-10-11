<?php
namespace AdIntelligence\Client\Models\Contracts;

use AdIntelligence\Client\Repositories\Contracts\RepositoryInterface;

/**
 * Trait TaskTrait
 * @package AdIntelligence\Client\Models\Contracts
 */
trait TaskTrait
{
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
        } else {

        }
    }

    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = $value;

    }

}