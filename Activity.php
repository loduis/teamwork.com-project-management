<?php
namespace TeamWorkPm;

class Activity extends Rest\Model
{

    /**
     * List Latest Activity (across all projects)
     * GET /activity.xml
     * Lists the latest activity list new tasks etc. across all projects that the user has access to.
     *
     * @param int $maxProjects
     * @param int $maxItems
     * @return type
     */
    public function get($maxProjects = null, $maxItems = null)
    {
        $params      = array();
        $maxProjects = (int) $maxProjects;
        $maxItems    = (int) $maxItems;
        if ($maxProjects) {
            $params['maxProjects'] = $maxProjects;
        }
        if ($maxItems) {
            $params['maxItems'] = $maxItems;
        }
        return $this->rest->get("$this->_action", $params);
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
        $params      = array();
        $maxItems    = (int) $maxItems;
        if ($maxItems) {
            $params['maxItems'] = $maxItems;
        }

        return $this->rest->get("projects/$project_id/$this->_action", $params);
    }
}