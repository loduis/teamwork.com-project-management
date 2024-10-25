<?php

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Model;

class Board extends Model
{
    public function init()
    {
        $this->parent = 'board';
        $this->action = 'portfolio/boards';

        $this->fields = [
            'canEdit' => [
                'type' => 'boolean'
            ],
            'name' => [
                'type' => 'string'
            ],
            'displayOrder' => [
                'type' => 'string'
            ],
            'description' => [
                'type' => 'string'
            ],
            'deletedDate' => [
                'type' => 'string'
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
     * Get all the Portfolio Boards
     * GET /portfolio/boards
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function all()
    {
        return $this->rest->get("$this->action");
    }
}
