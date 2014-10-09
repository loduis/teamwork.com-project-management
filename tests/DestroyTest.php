<?php

class DestroyTest extends TestCase
{

    /**
     *
     * @test
     */
    public function deleteCategoryProject()
    {
        try {
            $category = TeamWorkPm\Factory::build('category/project');
            foreach ($category->getAll() as $c) {
                $this->assertTrue($category->delete($c->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteLink()
    {
        try {
            $link = TeamWorkPm\Factory::build('link');
            foreach($link->getAll() as $l) {
                $this->assertTrue($link->delete($l->id));
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCategoryLink()
    {
        try {
            $category = TeamWorkPm\Factory::build('category/link');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($category->getByProject($p->id) as $c) {
                    $this->assertTrue($category->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

        /**
     * @test
     */
    public function deleteMessageReply()
    {

        try {
            $reply = TeamWorkPm\Factory::build('message/reply');
            $reply->delete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $message = TeamWorkPm\Factory::build('message');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($message->getByProject($p->id) as $m) {
                    foreach ($reply->getByMessage($m->id) as $r) {
                        $this->assertTrue($reply->delete($r->id));
                    }
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     *
     * @test
     */
    public function deleteMessage()
    {
        try {
            $message = TeamWorkPm\Factory::build('message');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($message->getByProject($p->id) as $m) {
                    $this->assertTrue($message->delete($m->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
        try {
            $message = TeamWorkPm\Factory::build('message');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($message->getByProject($p->id, true) as $m) {
                    $this->assertTrue($message->delete($m->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCategoryMessage()
    {
        try {
            $category = TeamWorkPm\Factory::build('category/message');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($category->getByProject($p->id) as $c) {
                    $this->assertTrue($category->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteTime()
    {
        //$this->fail('Delete time api bug.');
        try {
            $time = TeamWorkPm\Factory::build('time');
            foreach($time->getAll() as $t) {
                $this->assertTrue($time->delete($t->id));
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteCompany()
    {

        $company = TeamWorkPm\Factory::build('company');
        $fail = false;
        try {
            foreach($company->getAll() as $c) {
                try {
                    $this->assertTrue($company->delete($c->id));
                } catch (\TeamWorkPm\Exception $e) {
                    $this->assertEquals(
                        "You can't delete the owner company",
                        $e->getMessage()
                    );
                    $fail = true;
                }
            }
        } catch(\Exception $e) {
            $this->fail($e->getMessage());
        }
        if (!$fail) $this->fail('An expected exception has not been raised.');
    }

    /**
     * @test
     */
    public function deletePeople()
    {
        $people = TeamWorkPm\Factory::build('people');
        try {
            $people->delete(0);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        $fail = false;

        try {
            $project_id = get_first_project_id();
            $rows = $people->getAll();
            if ($project_id) {
                foreach($rows as $p) {
                    try {
                        // delete from project
                        $this->assertTrue($people->delete($p->id, $project_id));
                    } catch (\TeamWorkPm\Exception $e) {
                        $message = $e->getMessage();
                        $this->assertContains($e->getMessage(), [
                            'User is not on this project',
                            "This user is the only user on the " .
                            "project and can't be removed"
                        ]);
                    }
                }
            }
            foreach($rows as $p) {
                try {
                    // delete from account
                    $this->assertTrue($people->delete($p->id));
                } catch (\TeamWorkPm\Exception $e) {
                    $this->assertEquals(
                        "Site owner can not be deleted",
                        $e->getMessage()
                    );
                    $fail = true;
                }
            }
        } catch(\Exception $e) {
            $this->fail($e->getMessage());
        }
        if (!$fail) $this->fail('An expected exception has not been raised.');
    }

    public function deleteMilestoneComments()
    {
        try {
            $milestone = TeamWorkPm\Factory::build('milestone');
            $comment   = TeamWorkPm\Factory::build('comment/milestone');
            foreach ($milestone->getAll() as $m) {
                foreach ($comment->getRecent($m->id) as $c) {
                    $this->assertTrue($comment->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteMilestone()
    {
        try {
            $milestone = TeamWorkPm\Factory::build('milestone');
            foreach ($milestone->getAll() as $m) {
                $this->assertTrue($milestone->delete($m->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteNotebook()
    {
        try {
            $notebook = TeamWorkPm\Factory::build('notebook');
            $notebook->delete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            foreach ($notebook->getAll() as $n) {
                $this->assertTrue($notebook->delete($n->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     *
     * @test
     */
    public function deleteCategoryNotebook()
    {
        try {
            $category = TeamWorkPm\Factory::build('category/notebook');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($category->getByProject($p->id) as $c) {
                    $this->assertTrue($category->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteTask()
    {
        try {
            $taskList = TeamWorkPm\Factory::build('task/list');
            $task     = TeamWorkPm\Factory::build('task');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach ($taskList->getByProject($p->id) as $l) {
                    foreach ($task->getByTaskList($l->id) as $t) {
                        $this->assertTrue($task->delete($t->id));
                    }
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    /**
     *
     * @test
     */
    public function deleteTaskList()
    {
        try {
            $taskList = TeamWorkPm\Factory::build('task/list');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach ($taskList->getByProject($p->id) as $l) {
                    $this->assertTrue($taskList->delete($l->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCommentFile()
    {
        try {
            $comment = TeamWorkPm\Factory::build('comment/file');
            $file = TeamWorkPm\Factory::build('file');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach ($file->getByProject($p->id) as $f) {
                    foreach($comment->getRecent($f->id) as $c) {
                        $this->assertTrue($comment->delete($c->id));
                    }
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteFile()
    {
        try {
            $file = TeamWorkPm\Factory::build('file');
            $file->delete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($file->getByProject($p->id) as $c) {
                    $this->assertTrue($file->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     *
     * @test
     */
    public function deleteCategoryFile()
    {
        try {
            $category = TeamWorkPm\Factory::build('category/file');
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                foreach($category->getByProject($p->id) as $c) {
                    $this->assertTrue($category->delete($c->id));
                }
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteMeStatus()
    {
        try {
            $status = TeamWorkPm\Factory::build('me/status');
            $status->delete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $object = $status->get();
            if (isset($object->id)) {
                $this->assertTrue($status->delete($object->id));
                $object = $status->get();
                $this->assertFalse(isset($object->id));
            }
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deletePeopleStatus()
    {
        try {
            $status = TeamWorkPm\Factory::build('people/status');
            $status->delete(0);
            $this->fail('An expected exception has not been raised.');
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            foreach ($status->getAll() as $s) {
                $this->assertTrue($status->delete($s->id));
            }
        } catch(\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteProject()
    {
        try {
            $project  = TeamWorkPm\Factory::build('project');
            foreach ($project->getAll() as $p) {
                $this->assertTrue($project->delete($p->id));
            }
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
