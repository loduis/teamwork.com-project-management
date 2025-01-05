<?php

declare(strict_types = 1);

namespace TeamWorkPm\Portfolio;

use TeamWorkPm\Factory;
use TeamWorkPm\Rest\Response\Model as Response;
use TeamWorkPm\Rest\Resource\Model;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/portfolio-boards/get-portfolio-boards-json
 */
class Board extends Model
{
    protected ?string $parent = 'board';

    protected ?string $action = 'portfolio/boards';

    protected string|array $fields = "portfolio.boards";

    public function getColumns(int $id): Response
    {
        return Factory::portfolioColumn()->getByBoard($id);
    }
}
