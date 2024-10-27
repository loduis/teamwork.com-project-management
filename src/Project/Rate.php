<?php

namespace TeamWorkPm\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Response\Model as Response;
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
                        if (!is_scalar($value)) {
                            /** @disregard P1013 */
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
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $projectId, object|array $params = [])
    {
        return $this->rest->get("projects/$projectId/$this->actions", $params);
    }

    /**
     * @param int $projectId
     * @param object|array $data
     *
     * @return bool
     * @throws Exception
     */
    public function set(int $projectId, object|array $data)
    {
        return $this->rest->post("projects/$projectId/$this->actions", $data);
    }
}
