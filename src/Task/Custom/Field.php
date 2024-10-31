<?php

namespace TeamWorkPm\Task\Custom;

use TeamWorkPm\Custom\Field as Base;

class Field extends Base
{
    public function create(array $data)
    {
        $data['entity'] = 'task';

        return parent::create($data);
    }

    public function all(array $params = [])
    {
        $params['entities'] = 'task';

        return parent::all($params);
    }
}
