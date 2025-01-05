<?php

declare(strict_types = 1);

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\GetTrait;
use TeamWorkPm\Rest\Resource\SaveTrait;
use TeamWorkPm\Rest\Resource\UpdateTrait;
use TeamWorkPm\Rest\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/portfolio-boards/get-portfolio-boards-board-id-columns-json
 */
class Column extends Resource
{
    use GetTrait, UpdateTrait, SaveTrait, DestroyTrait;

    protected ?string $parent = 'column';

    protected ?string $action = 'portfolio/columns';

    protected string|array $fields = "portfolio.columns";

    /**
     * Columns inside a Portfolio Board
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getByBoard(int $id): Response
    {
        return $this->fetch("portfolio/boards/$id/columns");
    }

    /**
     * Add a column to the given Board
     *
     * @param array|object $data
     * @return int
     * @throws Exception
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $boardId = $data->pull('board_id');
        $this->validates([
            'board_id' => $boardId
        ], true);

        return $this->post("portfolio/boards/$boardId/columns", $data);
    }
}
