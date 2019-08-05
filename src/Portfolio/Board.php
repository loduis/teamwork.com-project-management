<?php

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Model;

class Board extends Model
{
    public function init()
    {
        $this->parent = 'board';
        $this->action = 'portfolio/boards';

        $this->fields = [
            'canEdit' => [
                'required' => false,
                'attributes' => ['type' => 'boolean'],
            ],

            'name' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'displayOrder' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'description' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
            ],

            'deletedDate' => [
                'required' => false,
                'attributes' => ['type' => 'string'],
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
     * Get all the Portfolio Boards
     * GET /portfolio/boards
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getAll()
    {
        return $this->rest->get("$this->action");
    }
}
