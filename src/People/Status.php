<?php

declare(strict_types = 1);

namespace TeamWorkPm\People;

use TeamWorkPm\Exception;
use TeamWorkPm\Rest\Resource;
use TeamworkPm\Rest\Resource\SaveTrait;
use TeamWorkPm\Rest\Response\Model as Response;

class Status extends Resource
{
    use SaveTrait;

    protected ?string $parent = 'userStatus';

    protected ?string $action = 'status';

    protected string|array $fields = "people.status";

    /**
     *
     * @param object|array $params Optional query parameters
     * @return Response
     * @throws Exception
     */
    public function all(object|array $params = []): Response
    {
        return $this->fetch("statuses", $params);
    }

    /**
     * Retrieve a Persons Status
     *
     * @param int $personId
     * @return Response
     * @throws Exception
     */
    public function get(int $personId)
    {
        return $this->fetch($this->resolvePath($personId));
    }

    /**
     * Create a User Status
     *
     * @param array|object $data
     * @return int
     * @throws Exception
     */
    public function create(array|object $data): int
    {
        $data = arr_obj($data);

        $personId = $data->pull('person_id');

        $this->validates([
            'person_id' => $personId
        ]);
        $path = $this->resolvePath($personId);

        return $this->post($path, $data);
    }

    /**
     * Update User Status | Update my Status
     *
     * @param array|object $data
     *
     * @return bool
     * @throws Exception
     */
    public function update(array|object $data): bool
    {
        $data = arr_obj($data);
        $id = (int) $data->pull('id');

        $this->validates([
            'id' => $id
        ]);

        $personId = $data->pull('person_id');

        $path = $this->resolvePath($personId);

        return $this->put("$path/$id", $data);
    }

    /**
     * Delete a Persons Status
     *
     * @param int $id
     * @param ?int $personId
     *
     * @return bool
     * @throws Exception
     */
    public function delete(int $id, ?int $personId = null)
    {
        $path = $this->resolvePath($personId);

        return $this->del("$path/$id");
    }

    protected function resolvePath(?int $personId): string
    {
        return 'people' .
            ($personId !== null ? "/$personId" : '') . '/' .
            $this->action;
    }
}
