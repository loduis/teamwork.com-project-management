<?php

namespace TeamWorkPm\Project\Custom;

use TeamWorkPm\Custom\Field as Base;

class Field extends Base
{
    public function create(array $data)
    {
        $data['entity'] = 'project';

        return parent::create($data);
    }

    public function all(array $params = [])
    {
        $params['entities'] = 'project';

        return parent::all($params);
    }
}
