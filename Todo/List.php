<?php
/**
 * Project:     TeamWorkPmPhpApi
 * File:        TodoList.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please join the
 * Smarty mailing list. Send a blank e-mail to
 * smarty-discussion-subscribe@googlegroups.com
 *
 * @author Loduis Madariaga Barrios
 * @package TeamWorkPmPhpApi
 * @version 0.0.1-dev
 */

class TeamWorkPm_Todo_List extends TeamWorkPm_Model
{
    protected $_fields = array(
            'name'=>true,
            'todo_list_template_id'=>false,
            'milestone_id'=>false,
            'description'=>false,
            'todo_list_template_task_date'=>false,
            'tracked'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
            'todo_list_template_task_assignto'=>false
    );

    private static $_instance;

    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }

    public function getByProjectId($id, $filter = 'all')
    {
        if (is_numeric($id)) {
            return $this->_get("projects/$id/todo_lists", "filter=$filter");
        }
        return null;
    }
    
    public function reOrder($project_id, array $request)
    {
        return $this->_post("projects/$project_id/todo_lists/reorder.xml", $request);
    }
}