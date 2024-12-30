<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Rest\Resource;
use TeamWorkPm\Rest\Response\Model as Response;

class Me extends Resource
{
    protected ?string $action = 'me';

    public function get(): Response
    {
        return $this->fetch("$this->action");
    }

    /**
     * Current User Summary Stats
     *
     * @return Response
     */
    public function getStats(): Response
    {
        return $this->fetch('stats');
    }

    /**
     * Get all your Running Timers
     *
     * @param int $id
     * @param array|object $params
     * @return Response
     * @throws Exception
     */
    public function getTimers(): Response
    {
        return $this->fetch("$this->action/timers");
    }
}
