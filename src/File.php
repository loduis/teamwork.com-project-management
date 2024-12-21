<?php

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;
use TeamWorkPm\Rest\CopyAndMoveTrait;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/files/get-files-id-json
 */
class File extends Rest\Resource
{
    use CopyAndMoveTrait;

    protected ?string $parent = 'file';

    protected ?string $action = 'files';

    protected string|array $fields = [];

    /**
     * Get all files in projects
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function all(): Response
    {
        return $this->fetch("$this->action");
    }

    /**
     * Get a Single File
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function get(int $id): Response
    {
        return $this->fetch("$this->action/$id");
    }

    /**
     * List Files on a Project
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $projectId)
    {
        return Factory::projectFile()->all($projectId);
    }

    /**
     * List Files on a Task
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getByTask(int $id)
    {
        return Factory::taskFile()->all($id);
    }

    /**
     * Upload a File (Classic)
     *
     * @param string|array $files
     * @return array
     * @throws Exception
     */
    public function upload(string|array $files): array
    {
        $files = (array)$files;
        $pending_file_attachments = [];
        foreach ($files as $filename) {
            if (!is_file($filename)) {
                throw new Exception("Not file exist $filename");
            }
        }
        foreach ($files as $filename) {
            $params = ['file' => curl_file_create($filename)];
            $pending_file_attachments[] = $this->post(
                'pendingfiles',
                $params
            );
        }

        return $pending_file_attachments;
    }

    /**
     * Add a File to a Project
     * Add a File to a Task
     *
     *
     * @param array $data
     *
     * @return int
     * @throws Exception
     */
    public function add(object|array $data): mixed
    {
        $data = arr_obj($data);
        $projectId = $data->pull('project_id');
        $taskId = $data->pull('task_id');
        $id = $data->pull('id');
        $files = $data->pull('files');
        if ($files !== null) {
            $files = is_string($files) ? (array) $files : $files->toArray();
            if ($id !== null) {
                $files = $files[0];
            }
            $data[
                $taskId ?
                'pending_file_attachments':
                'pending_file_ref'
            ] = $this->upload($files);
        }
        if ($id !== null) {
            if (empty($data['pending_file_ref'])) {
                throw new Exception('Required field pending_file_ref');
            }
            $params = [
                'pendingFileRef' => ((array) $data['pending_file_ref'])[0]
            ];
            if (!empty($data->description)) {
                $params['description'] = $data['description'];
            }
            return $this->notUseFields()
                ->post("$this->action/$id", ['fileversion' => $params]);
        }

        if (!($taskId || $projectId)) {
            throw new Exception('Required field project_id or task_id');
        }

        return $projectId ?
            Factory::projectFile()->add($projectId, $data):
            Factory::taskFile()->add($taskId, $data);
    }

    /**
     * Get a short URL for sharing a File
     *
     * @param integer $id
     * @return string
     */
    public function getSharedLink(int $id): string
    {
        return $this->fetch("$this->action/$id/sharedlink")->url;
    }

    /**
     * Delete a File from a Project
     *
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        return $this->del("$this->action/$id");
    }
}
