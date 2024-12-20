<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model;

class Notebook extends Rest\Resource
{
    protected function init()
    {
        $this->fields = [
            'name' => true,
            'description' => true,
            'content' => true,
            'project_id' => [
                'required' => true,
                'type' => 'integer'
            ],
            'notify' => false,
            'category_id' => [
                'type' => 'integer'
            ],
            'category_name' => false,
            'grant-access-to' => false,
            'version' => false,
            'private' => [
                'type' => 'boolean'
            ],
        ];
    }

    /**
     * @param $id
     * @param array $params
     *
     * @return Model
     * @throws Exception
     */
    public function get($id, array $params = [])
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->fetch("$this->action/$id", $params);
    }

    /**
     * List All Notebooks
     *
     * GET /notebooks.xml?includeContent=[true|false]
     *
     * Lists all notebooks on projects that the authenticated user is associated with.
     * By default, the actual notebook HTML content is not returned.
     * You can pass includeContent=true to return the notebook HTML content with the notebook data
     *
     * @param bool $includeContent
     *
     * @return Model
     * @throws Exception
     */
    public function getAll($includeContent = false)
    {
        $includeContent = (bool)$includeContent;
        return $this->fetch("$this->action", [
            'includeContent' => $includeContent ? 'true' : 'false',
        ]);
    }

    /**
     * List Notebooks on a Project
     *
     * GET /projects/#{project_id}/notebooks.xml
     *
     * This lets you query the list of notebooks for a project.
     * By default, the actual notebook HTML content is not returned.
     * You can pass includeContent=true to return the notebook HTML content with the notebook data
     *
     * @param int $projectId
     *
     * @param bool $includeContent
     *
     * @return Model
     * @throws Exception
     */
    public function getByProject($projectId, $includeContent = false)
    {
        $projectId = (int)$projectId;
        if ($projectId <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $includeContent = (bool)$includeContent;
        return $this->fetch("projects/$projectId/$this->action", [
            'includeContent' => $includeContent ? 'true' : 'false',
        ]);
    }

    /**
     * Lock a Single Notebook For Editing
     *
     * PUT /notebooks/#{id}/lock
     *
     * Locks the notebook and all versions for editing.
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function lock($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->put("$this->action/$id/lock");
    }

    /**
     * Unlock a Single Notebook
     *
     * PUT /notebooks/#{id}/unlock
     *
     * Unlocks a locked notebook so it can be edited again.
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function unlock($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->put("$this->action/$id/unlock");
    }

    /**
     * Create a Single Notebook
     *
     * POST /projects/#{project_id}/notebooks
     * This command will create a single notebook.
     * Content must be valid XHMTL
     * You not not need to include <html>, <head> or <body> tags
     *
     * @throws Exception
     */
    public function create(array $data)
    {
        $projectId = empty($data['project_id']) ? 0 : (int)$data['project_id'];
        if ($projectId <= 0) {
            throw new Exception('Required field project_id');
        }
        return $this->post("projects/$projectId/$this->action", $data);
    }

    /**
     * Update Notebook
     *
     * PUT /notebooks/#{notebook_id}
     *
     * Modifies an existing notebook.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(array $data)
    {
        $id = empty($data['id']) ? 0 : (int)$data['id'];
        if ($id <= 0) {
            throw new Exception('Required field id');
        }
        return $this->put("$this->action/$id", $data);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    final public function save(array $data)
    {
        return array_key_exists('id', $data)
            ? $this->update($data)
            : $this->insert($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function delete($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->del("$this->action/$id");
    }
}
