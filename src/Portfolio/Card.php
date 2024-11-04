<?php

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;

class Card extends Model
{
    public function init()
    {
        $this->parent = 'card';
        $this->action = 'portfolio/cards';

        $this->fields = [
            'projectId' => [
                'type' => 'string'
            ],

            // These are only used by the update method
            'cardId' => [
                'type' => 'string',
                'sibling' => true,
            ],

            'columnId' => [
                'type' => 'string',
                'sibling' => true,
            ],

            'oldColumnId' => [
                'type' => 'string',
                'sibling' => true,
            ],

            'positionAfterId' => [
                'type' => 'integer',
                'sibling' => true,
            ],
        ];
    }

    /**
     * Get all the Columns for a Portfolio Column
     * GET /portfolio/columns/{columnId}/cards
     *
     * @param int $columnId
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getAllForColumn($columnId)
    {
        $columnId = (int)$columnId;
        if ($columnId <= 0) {
            throw new Exception('Invalid param columnId');
        }

        return $this->fetch("portfolio/columns/$columnId/cards");
    }

    /**
     * Adds a project to the given board
     *
     * @param array $data
     *
     * @return int
     */
    public function create(array $data)
    {
        $columnId = empty($data['columnId']) ? 0 : (int)$data['columnId'];
        if ($columnId <= 0) {
            throw new Exception('Required field columnId');
        }
        unset($data['columnId']);

        if (empty($data['projectId'])) {
            throw new Exception('Required field projectId');
        }

        return $this->post("portfolio/columns/$columnId/cards", $data);
    }

    /**
     * Moves the given card from one board to another
     *
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array $data)
    {
        $cardId = empty($data['id']) ? 0 : (int)$data['id'];
        if ($cardId <= 0) {
            throw new Exception('Required field id');
        }
        $data['cardId'] = $data['id'];
        unset($data['id']);

        if (empty($data['columnId'])) {
            throw new Exception('Required field columnId');
        }

        if (empty($data['oldColumnId'])) {
            throw new Exception('Required field oldColumnId');
        }

        return $this->put("$this->action/$cardId/move", $data);
    }
}
