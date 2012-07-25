<?php

class TeamWorkPm_Activity extends TeamWorkPm_Rest_Model
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
    public function get($maxProjects = NULL, $maxItems = NULL)
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
        return $this->_get("$this->_action", $params);
    }

    /**
     * List Latest Activity (for a project)
     * GET /projects/#{project_id}/activity.xml
     * Lists the latest activity list new tasks etc. for a given project.
     *
     * @param int $project_id
     * @param int $maxItems
     * @return TeamWorkPm_Response_Model
     */
    public function getByProject($project_id, $maxItems = NULL)
    {
        $project_id = (int) $project_id;
        $params      = array();
        $maxItems    = (int) $maxItems;
        if ($maxItems) {
            $params['maxItems'] = $maxItems;
        }

        return $this->_get("projects/$project_id/$this->_action", $params);
    }
}