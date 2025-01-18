<?php

namespace TeamWorkPm\Rest\Resource;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Response\Model as Response;

trait ReactTrait
{
    /**
     * React to a Resource
     *
     * @param integer $id
     * @return boolean
     */
    public function react(int $id, array|object $data = []): bool | Response
    {
        $data = $this->validateReact($data);

        return $this->notUseFields()
            ->put("$this->action/$id/react", $data);
    }

    /**
     * Un react to a Resource
     *
     * @param integer $id
     * @return boolean
     */
    public function unReact(int $id, array|object $data = []): bool | Response
    {
        $data = $this->validateReact($data);

        return $this->notUseFields()
            ->put("$this->action/$id/unreact", $data);
    }

    private function validateReact($data)
    {
        $data = arr_obj($data);
        $type = $data->pull('type');
        /** @disregard */
        $reactionType = $data->reactionType ?? $type;
        if ($reactionType && !in_array($reactionType, ['heart', 'like', 'dislike', 'joy', 'frown'])) {
            throw new Exception("Invalid field reactionType: " . $reactionType);
        }
        /** @disregard */
        if ($data->get === true) {
            /** @disregard */
            $data->get = 'reactions';
        }

        if ($type) {
            /** @disregard */
            $data->reactionType = $type;
        }

        return $data;
    }
}