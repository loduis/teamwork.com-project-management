<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

trait StoreTrait
{
    use UpdateTrait, SaveTrait;

    /**
     * @param array|object $data
     * @return int
     */
    public function create(array|object $data): int
    {
        /**
         * @var int
         */
        return $this->post((string) $this->action, $data);
    }
}