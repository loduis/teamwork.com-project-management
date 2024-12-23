<?php

declare(strict_types = 1);

namespace TeamWorkPm\Me;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Resource\DestroyTrait;
use TeamworkPm\Rest\Resource\StoreTrait;
use TeamWorkPm\Rest\Response\Model as Response;

class Status extends Resource
{
    use StoreTrait, DestroyTrait;

    protected ?string $parent = 'userStatus';

    protected ?string $action = 'me/status';

    protected string|array $fields = 'me.status';

    /**
     * Retrieve a Persons Status
     *
     * @return Response
     * @throws Exception
     */
    public function get()
    {
        return $this->fetch("$this->action");
    }
}
