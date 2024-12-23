<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource\Model;
use TeamWorkPm\Rest\Resource\CopyAndMoveTrait;
use TeamWorkPm\Rest\Resource\Project\ActionTrait as ProjectTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/notebooks/get-notebooks-json
 */
class Notebook extends Model
{
    use ProjectTrait, CopyAndMoveTrait;

    protected ?string $parent = 'notebook';

    protected ?string $action = 'notebooks';

    protected string|array $fields = 'notebooks';

    /**
     * Lock a Single Notebook For Editing
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function lock(int $id)
    {
        return $this->put("$this->action/$id/lock");
    }

    /**
     * Unlock a Single Notebook
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function unlock(int $id)
    {
        return $this->put("$this->action/$id/unlock");
    }
}
