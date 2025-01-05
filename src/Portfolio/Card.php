<?php

declare(strict_types = 1);

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamWorkPm\Rest\Resource\GetTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/portfolio-boards/get-portfolio-boards-json
 */
class Card extends Resource
{
    use DestroyTrait, GetTrait;

    protected ?string $parent = 'card';

    protected ?string $action = 'portfolio/cards';

    protected string|array $fields = "portfolio.cards";

    /**
     * Undocumented function
     *
     * @param integer $columnId
     * @param integer $projectId
     * @return integer
     */
    public function create(int $columnId, int $projectId): int
    {
        $this->validates([
            'column_id' => $columnId,
            'project_id' => $projectId
        ], true);

        return $this->post(
            "portfolio/columns/$columnId/cards", compact('projectId')
        );
    }

    /**
     * Get Cards inside a Portfolio Column
     *
     * @param integer $id
     * @return Response
     */
    public function getByColumn(int $id): Response
    {
        return $this->fetch("portfolio/columns/$id/cards");
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param integer $oldColumnId
     * @param integer $columnId
     * @param integer $positionAfterId
     * @return boolean
     */
    public function move(int $id, int $oldColumnId, int $columnId, int $positionAfterId): bool
    {
        return $this
            ->notUseFields()
            ->put("$this->action/$id/move", compact(
                'oldColumnId',
                'columnId',
                'positionAfterId'
            )
        );
    }
}
