<?php

namespace TeamWorkPm;

class Notebook extends Rest\Model
{
    protected function init()
    {
        $this->fields = [
            'name' => true,
            'description'=>true,
            'content'=>true,
            'notify'=>false,
            'category_id'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'integer'
                ]
            ],
            'category_name'=> false,
            'private'=>[
                'required'=>false,
                'attributes'=>[
                    'type'=>'boolean'
                ]
            ]
        ];
    }

    /**
     * @param $id
     * @param array $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function get($id, array $params = [])
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->get("$this->action/$id", $params);
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
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getAll($includeContent = false)
    {
        $includeContent = (bool) $includeContent;
        return $this->rest->get("$this->action", [
          'includeContent'=>$includeContent ? 'true' : 'false'
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
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByProject($projectId, $includeContent = false)
    {
        $projectId = (int) $projectId;
        if ($projectId <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $includeContent = (bool) $includeContent;
        return $this->rest->get("projects/$projectId/$this->action", [
          'includeContent'=>$includeContent ? 'true' : 'false'
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
     * @throws \TeamWorkPm\Exception
     */
    public function lock($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->put("$this->action/$id/lock");
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
     * @throws \TeamWorkPm\Exception
     */
    public function unlock($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->put("$this->action/$id/unlock");
    }

    /**
     * Create a Single Notebook
     *
     * POST /projects/#{project_id}/notebooks
     * This command will create a single notebook.
     * Content must be valid XHMTL
     * You not not need to include <html>, <head> or <body> tags
     *
     * @throws \TeamWorkPm\Exception
     */
    public function insert(array $data)
    {
        $projectId = empty($data['project_id']) ? 0: (int) $data['project_id'];
        if ($projectId <= 0) {
            throw new \TeamWorkPm\Exception('Required field project_id');
        }
        return $this->rest->post("projects/$projectId/$this->action", $data);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    final public function save(array $data)
    {
        return $this->insert($data);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function delete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->delete("$this->action/$id");
    }
}
