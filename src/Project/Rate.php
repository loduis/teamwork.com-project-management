<?php

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use function TeamWorkPm\array_reduce;

use TeamWorkPm\Rest\Resource;

class Rate extends Resource
{
    protected ?string $parent = 'rates';

    protected ?string $actions = 'rates';

    protected function init()
    {
        $this->fields = [
            'project_default' => [
                'type' => 'integer',
                'transform' => 'dash'
            ],
            'users' => [
                'type' => 'array',
                'transform' => [null, function ($value) {
                    return array_reduce($value, function ($acc, $value, $key) {
                        if (is_iterable($value)) {
                            $value = arr_obj($value)->rate;
                        }
                        $acc[$key] = [
                            'rate' => (float)$value,
                        ];
                        return $acc;
                    }, []);
                }]
            ]
        ];
    }

    /**
     * @param int $projectId
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get(int $projectId, array $params = [])
    {
        return $this->rest->get("projects/$projectId/$this->actions", $params);
    }

    /**
     * @param int $projectId
     * @param array $params
     *
     * @return bool
     * @throws Exception
     */
    public function set(int $projectId, array $data)
    {
        return $this->rest->post("projects/$projectId/$this->actions", $data);
    }
}
