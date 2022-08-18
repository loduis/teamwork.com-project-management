<?php

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;

class Column extends Model
{
    public function init()
    {
        $this->parent = 'column';
        $this->action = 'portfolio/columns';

        $this->fields = [
            'name' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'displayOrder' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'sortOrder' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'deletedDate' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'dateUpdated' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'hasTriggers' => [
                'required' => false,
                'attributes' => ['type' => 'boolean'],
            ],

            'sort' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'canEdit' => [
                'required' => false,
                'attributes' => ['type' => 'boolean'],
            ],

            'id' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'dateCreated' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'color' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'deleted' => [
                'required' => false,
                'attributes' => ['type' => 'boolean'],
            ],
        ];
    }

    /**
     * Get all the Columns for a Portfolio Board
     * GET /portfolio/boards/{boardId}/columns
     *
     * @param int $boardId
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getAllForBoard($boardId)
    {
        $boardId = (int)$boardId;
        if ($boardId <= 0) {
            throw new Exception('Invalid param boardId');
        }

        return $this->rest->get("portfolio/boards/$boardId/columns");
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function insert(array $data)
    {
        $boardId = empty($data['board_id']) ? 0 : (int)$data['board_id'];
        if ($boardId <= 0) {
            throw new Exception('Required field board_id');
        }
        unset($data['board_id']);

        return $this->rest->post("portfolio/boards/$boardId/columns", $data);
    }
}
