<?php

namespace TeamWorkPm\Project;

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
                'transform' => [null, function (array|object $value): array {
                    return array_reduce($value, function (array $acc, $value, $key) {
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
     * @throws \TeamWorkPm\Exception
     */
    public function get(int $projectId, array $params = [])
    {
        return $this->rest->get("projects/$projectId/$this->actions", $params);
    }

    /**
     * @param int $projectId
     * @param array $data
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function set(int $projectId, array $data)
    {
        return $this->rest->post("projects/$projectId/$this->actions", $data);
    }
}
