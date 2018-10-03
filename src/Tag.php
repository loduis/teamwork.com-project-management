<?php

namespace TeamWorkPm;

class Tag extends Model
{
    protected function init()
    {
        $this->fields = [
            'name' => true,
            'color' => false,
        ];
        $this->action = 'tags';
    }

    /**
     * Retrieve a single tag
     *
     * @param int $id
     * @param null $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function get($id, $params = null)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->get("$this->action/$id", $params);
    }

    /**
     * Get all Tags
     * GET /tags
     *
     * @return \TeamWorkPm\Response\Model
     */
    public function getAllTags()
    {
        return $this->rest->get("$this->action");
    }

    /**
     * Get all Tags for a given resource.
     *
     * GET /{resource}/{id}/tags
     *
     * @param string $resource
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     */
    public function getAllTagsForResource($resource = '', $id = null)
    {
        return $this->rest->get("$resource/$id/$this->action");
    }

    /**
     * Create a tag
     * POST /tags.xml
     * This will create a new tag.
     *
     * @param array $data
     *
     * @return int
     */
    public function insert(array $data)
    {
        return $this->rest->post("$this->action", $data);
    }

    /**
     * Update Tag
     * PUT /tags/#{tag_id}
     * Modifies an existing tag.
     *
     * @param array $data
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function update(array $data, $resource = '', $resource_id = 0)
    {
        $id = empty($data['id']) ? 0 : (int)$data['id'];
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        unset($data['id']);
        $action = "$this->action/$id";
        if (!empty($resource) && !empty($resource_id)) {
            $action = "$resource/$resource_id/$this->action";
        }
        return $this->rest->put($action, $data);
    }

    public function addTagToResource($resource = '', $id = null)
    {

        return $this->rest->put("$resource/$id/$this->action");
    }
}