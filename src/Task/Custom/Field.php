<?php

namespace TeamWorkPm\Task\Custom;

use TeamWorkPm\Custom\Field as Base;

class Field extends Base
{
    public function insert(array $data)
    {
        $data['entity'] = 'task';

        return parent::insert($data);
    }

    public function all(array $params = [])
    {
        $params['entities'] = 'task';

        return parent::all($params);
    }
}
