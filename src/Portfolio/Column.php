<?php

declare(strict_types = 1);

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Response\Model as Response;

class Column extends Model
{
    public function init()
    {
        $this->parent = 'column';
        $this->action = 'portfolio/columns';

        $this->fields = [
            'name' => [
                'type' => 'string'
            ],

            'displayOrder' => [
                'type' => 'string'
            ],

            'sortOrder' => [
                'type' => 'string'
            ],

            'deletedDate' => [
                'type' => 'string'
            ],

            'dateUpdated' => [
                'type' => 'string'
            ],

            'hasTriggers' => [
                'type' => 'boolean'
            ],

            'sort' => [
                'type' => 'string'
            ],

            'canEdit' => [
                'type' => 'boolean'
            ],

            'id' => [
                'type' => 'string'
            ],

            'dateCreated' => [
                'type' => 'string'
            ],

            'color' => [
                'type' => 'string'
            ],

            'deleted' => [
                'type' => 'boolean'
            ],
        ];
    }

    /**
     * Get all the Columns for a Portfolio Board
     * GET /portfolio/boards/{boardId}/columns
     *
     * @param int $boardId
     *
     * @return Response
     * @throws Exception
     */
    public function getAllForBoard($boardId)
    {
        $boardId = (int)$boardId;
        if ($boardId <= 0) {
            throw new Exception('Invalid param boardId');
        }

        return $this->fetch("portfolio/boards/$boardId/columns");
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function create(array $data)
    {
        $boardId = empty($data['board_id']) ? 0 : (int)$data['board_id'];
        if ($boardId <= 0) {
            throw new Exception('Required field board_id');
        }
        unset($data['board_id']);

        return $this->post("portfolio/boards/$boardId/columns", $data);
    }
}
