<?php

namespace TeamWorkPm\Project\Custom;

use TeamWorkPm\Custom\Field as Base;
use TeamWorkPm\Response\Model as Response;

class Field extends Base
{
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $data['entity'] = 'project';

        return parent::create($data);
    }

    public function all(object|array $params = []): Response
    {
        $params = arr_obj($params);

        $params['entities'] = 'project';

        return parent::all($params);
    }
}
