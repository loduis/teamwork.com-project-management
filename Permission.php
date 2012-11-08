<?php

class TeamWorkPm_Permission extends TeamWorkPm_Rest_Model
{
    /**
     * Add a new user to a project
     * POST /projects/{id}/people/{id}
     * Add a user to a project. Default permissions setup in Teamwork will be used.
     * Response
     * Returns HTTP status code 201 (Created) on success.
     * @param int $people_id id de la persona creada
     * @param int $project_id id del projecto creado
     *
     * @return bool
     */
    public function addUserToProject($people_id, $project_id)
    {
        $people_id  = (int) $people_id;
        $project_id = (int) $project_id;
        if ($people_id <= 0) {
            throw new TeamWorkPm_Exception('Required parameter people_id');
        }
        return $this->_post("projects/$project_id/people/$people_id");
    }
}