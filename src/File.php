<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model;

class File extends Rest\Resource
{
    protected function init()
    {
        $this->fields = [
            'pending_file_ref' => true,
            'description' => false,
            'category_id' => [
                'type' => 'integer'
            ],
            'category_name' => false,
            'private' => false,
        ];
    }

    /**
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function get($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception('Invalid param id');
        }
        return $this->rest->get("$this->action/$id");
    }

    /**
     * List Files on a Project
     *
     * GET /projects/#{project_id}/files.xml
     *
     * This lets you query the list of files for a project.
     *
     * @param int $project_id
     *
     * @return Model
     * @throws Exception
     */
    public function getByProject($project_id)
    {
        $project_id = (int)$project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        return $this->rest->get("projects/$project_id/$this->action");
    }

    /**
     * Step 1. Upload the file
     *
     * POST /pendingfiles
     *
     * Send your file to POST /pendingfiles.xml using the FORM field "file".
     * You will still need to authenticate yourself by passing your API token.
     *
     * If the upload is successful, you will get back something like:
     * tf_1706111559e0a49
     *
     * @param array $files
     * @return string
     * @throws Exception
     */
    public function upload($files)
    {
        $files = (array)$files;
        $pending_file_attachments = [];
        foreach ($files as $filename) {
            if (!is_file($filename)) {
                throw new Exception("Not file exist $filename");
            }
        }
        foreach ($files as $filename) {
            $params = ['file' => self::getFileParam($filename)];
            $pending_file_attachments[] = $this->rest->upload(
                'pendingfiles',
                $params
            );
        }

        return implode(',', $pending_file_attachments);
    }

    private static function getFileParam($filename)
    {
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename);
        }

        return "@$filename";
    }

    /**
     * Add a File to a Project
     *
     * POST /projects/#{file_id}/files
     *
     * @param array $data
     *
     * @return int File id
     * @throws Exception
     */
    public function save(array $data)
    {
        $project_id = empty($data['project_id']) ? 0 : (int)$data['project_id'];
        if ($project_id <= 0) {
            throw new Exception('Required field project_id');
        }
        if (empty($data['pending_file_ref']) && empty($data['filename'])) {
            throw new Exception('Required field pending_file_ref or filename');
        }
        if (empty($data['pending_file_ref'])) {
            $data['pending_file_ref'] = $this->upload($data['filename']);
        }
        unset($data['filename']);
        return $this->rest->post("projects/$project_id/files", $data);
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
        return $this->rest->delete("$this->action/$id");
    }
}
