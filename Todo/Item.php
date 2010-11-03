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

class TeamWorkPm_Todo_Item extends TeamWorkPm_Model
{
    protected $_fields = array(
        'content'=>true,
        'notify'=>array('required'=>false, 'attributes'=>array('type'=>'boolean=false')),
        'description'=>false,
        'due_date'=>array('required'=>false, 'attributes'=>array('type'=>'integer')),
        'private'=>array('required'=>false, 'attributes'=>array('type'=>'boolean')),
        'priority'=>array('required'=>false, 'attributes'=>array('type'=>'integer')),
        'responsible_party_id'=>false
    );

    private static $_instance;

    public function getInstance($company, $key)
    {
        if (null === self::$_instance) {
            self::$_instance = new self($company, $key, __CLASS__);
        }

        return self::$_instance;
    }


    public function getByTodoListId($id)
    {
        return $this->_get("todo_lists/$id/todo_items");
    }

    public function insert(array $data)
    {
        $todo_list_id = $data['todo_list_id'];
        if (empty($todo_list_id)) {
            throw new TeamWorkPm_Exception('Require field todo list id');
        }
        return $this->_post("todo_lists/$todo_list_id/todo_items", $data);
    }

    public function markAsComplete($id)
    {
        return $this->_put("todo_items/$id/complete");
    }

    public function markAsUnComplete($id)
    {
        return $this->_put("todo_items/$id/uncomplete");
    }

    public function reOrder($todo_list_id, array $request)
    {
        return $this->_post("todo_lists/$todo_list_id/todo_items/reorder", $request);
    }
}