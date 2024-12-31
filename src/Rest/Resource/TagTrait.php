<?php

declare(strict_types = 1);

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Factory;
use TeamWorkPm\Rest\Response\Model as Response;

trait TagTrait
{
     /**
     * Add Tags for a Resource
     *
     * @param int $id
     * @param string|array $data
     * @return bool
     */
    public function addTag(int $id, int|string|array $data): bool
    {
        return Factory::tag()->addTo("$this->action", $id, $data);
    }

    /**
     * Remove Tags for a Resource
     *
     * @param integer $id
     * @param integer|string|array $data
     * @return boolean
     */
    public function removeTag(int $id, int|string|array $data): bool
    {
        return Factory::tag()->removeTo("$this->action", $id, $data);
    }

    /**
     * List All Tags for a Resource
     *
     * @param integer $id
     * @return Response
     */
    public function getTags(int $id): Response
    {
        return Factory::tag()->allFor("$this->action", $id);
    }
}