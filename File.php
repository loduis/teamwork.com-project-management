<?php
namespace TeamWorkPm;

class File extends Rest\Model
{
    private $_id = null;

    protected function _init()
    {
        $this->_fields = array(
            'pending_file_ref' => true,
            'description'=>true,
            'category_id'=>array(
                'required'=>false,
                'attributes'=>array(
                    'type'=>'integer'
                )
            ),
            'category_name'=> false,
            'private'=>false
        );
    }
    /**
     * Get a Single File
     *
     * GET /files/#{file_id}
     *
     * This returns all details about an individual file.
     * Crucially - this call also returns the download URL which is valid only for an hour.
     *
     * @param type $id
     * @return type
     */
    public function get($id)
    {
        $id = (int) $id;
        return $this->_get("$this->_action/$id");
    }

    /**
     * List Files on a Project
     *
     * GET /projects/#{project_id}/files.xml
     *
     * This lets you query the list of files for a project.
     *
     * @param type $id
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($id)
    {
        $id = (int) $id;
        return $this->_get("projects/$id/$this->_action");
    }

    /**
     * Delete a File from a Project
     *
     * DELETE /files/#{file_id}
     *
     * This call deletes a file from a project.
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
    {
        $id = (int) $id;
        if (empty($id)) {
            throw new Exception('Require field id');
        }
        return $this->_delete("$this->_action/$id");
    }
    /**
     * Step 1. Upload the file
     *
     * POST /pendingfiles
     *
     * Send your file to POST /pendingfiles.xml using the FORM field "file".
     * You will still need to authenticate yourself by passing your API token.
     *
     * @param string $filename
     * @return string
     * @throws \TeamWorkPm\Exception
     */
    public function upload($filename)
    {
        if (file_exists($filename)) {
            return $this->_id = $this->_upload("pendingfiles", array(
              'file'=>'@' . $filename
            ));
        } else {
            throw new Exception("Not file exist $filename");
        }
    }

    /**
     * Add a File to a Project
     *
     * POST /projects/#{file_id}/files
     *
     * @param int $id
     * @param array $params [filename, category_id, category_name, description, private, pending_file_ref, project_id]
     * @return int File id
     * @throws \TeamWorkPm\Exception
     */
    public function addToProject($data = array())
    {
        $project_id = empty($data['project_id']) ? 0 : (int) $data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Require field project_id');
        }
        if (empty($data['pending_file_ref']) && empty($data['filename'])) {
            throw new Exception('Require field pending_file_ref or filename');
        }
        if (empty($data['category_id']) && empty($data['category_name'])) {
            throw new Exception('Require field category_id or category_name');
        }
        if (empty($data['pending_file_ref'])) {
            if (empty($data['filename'])) {
                throw new Exception('Require field filename.');
            }
            $pending_file_ref = $this->upload($data['filename']);
            $data['pending_file_ref'] = $pending_file_ref;
        }
        unset($data['filename']);
        return $this->_post("projects/$project_id/files", $data);
    }
}