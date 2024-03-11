<?php

namespace TeamWorkPm\Tests\Project;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

class PeopleTest extends TestCase
{
    private $model;
    private static $personId;
    private static $projectId;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('project/people');
        if (!self::$personId) {
            $people = Factory::build('people');
            foreach ($people->getAll() as $p) {
                if (!$p->siteOwner) {
                    self::$personId = (int)$p->id;
                    break;
                }
            }
        }
        if (!self::$projectId) {
            self::$projectId = get_first_project_id();
        }
    }

    /**
     * @test
     */
    public function add(): void
    {
        try {
            $add = $this->model->add(self::$projectId, self::$personId);
            $this->assertTrue($add);
        } catch (Exception $e) {
            $this->assertEquals('User is already on project', $e->getMessage());
        }
        try {
            $this->model->add(self::$projectId, self::$personId);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('User is already on project', $e->getMessage());
        }
    }

    /**
     * @depends add
     * @test
     */
    public function get(): void
    {
        $people = $this->model->get(self::$projectId, self::$personId);
        $this->assertTrue(isset($people->permissions));
    }

    /**
     * @depends      add
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        try {
            $this->model->update($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field project_id', $e->getMessage());
        }
        $data['project_id'] = self::$projectId;
        try {
            $this->model->update($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field person_id', $e->getMessage());
        }
        $data['person_id'] = self::$personId;
        $this->assertTrue($this->model->update($data));
    }

    /**
     * @depends add
     * @test
     */
    public function delete(): void
    {
        try {
            $this->model->delete(0, self::$personId);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $this->model->delete(self::$projectId, 0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param person_id', $e->getMessage());
        }
        $this->assertTrue(
            $this->model->delete(self::$projectId, self::$personId)
        );
    }

    public function provider()
    {
        return [
            [
                [
                    'view_messages_and_files' => 0,
                    'view_tasks_and_milestones' => 1,
                    'view_time' => 1,
                    'view_notebooks' => 0,
                    'view_risk_register' => 0,
                    'view_invoices' => 0,
                    'view_links' => 0,
                    'add_tasks' => 0,
                    'add_milestones' => 0,
                    'add_taskLists' => 0,
                    'add_messages' => 0,
                    'add_files' => 0,
                    'add_time' => 0,
                    'add_links' => 0,
                ],
            ],
        ];
    }
}
