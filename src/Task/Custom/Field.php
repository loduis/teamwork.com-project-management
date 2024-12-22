<?php

declare(strict_types = 1);

namespace TeamWorkPm\Task\Custom;

use TeamWorkPm\Response\Model as Response;
use TeamWorkPm\Custom\Field as Base;

class Field extends Base
{
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $data['entity'] = 'task';

        return parent::create($data);
    }

    public function all(object|array $params = []): Response
    {
        $params = arr_obj($params);

        $params['entities'] = 'task';

        return parent::all($params);
    }
}
