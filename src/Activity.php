<?php namespace TeamWorkPm;

class Activity extends Rest\Model
{

    protected function init()
    {
        $this->action = 'latestActivity';
    }
    /**
     * List Latest Activity (across all projects)
     * GET /activity.xml
     * Lists the latest activity list new tasks etc. across all projects that the user has access to.
     *
     * @param int $maxProjects
     * @param int $maxItems
     * @return type
     */
    public function getAll($maxItems = null, $onlyStarred = null)
    {
        $params      = [];
        $onlyStarred = (bool) $onlyStarred;
        $maxItems    = (int) $maxItems;
        if ($onlyStarred) {
            $params['onlyStarred'] = $onlyStarred;
        }
        if ($maxItems) {
            $params['maxItems'] = $maxItems;
        }
        return $this->rest->get("$this->action", $params);
    }

    /**
     * List Latest Activity (for a project)
     * GET /projects/#{project_id}/activity.xml
     * Lists the latest activity list new tasks etc. for a given project.
     *
     * @param int $project_id
     * @param int $maxItems
     * @return TeamWorkPm\Response\Model
     */
    public function getByProject($project_id, $maxItems = null)
    {
        $project_id = (int) $project_id;
        if ($project_id <= 0) {
            throw new Exception('Invalid param project_id');
        }
        $params      = [];
        $maxItems    = (int) $maxItems;
        if ($maxItems) {
            $params['maxItems'] = $maxItems;
        }

        return $this->rest->get("projects/$project_id/$this->action", $params);
    }
}