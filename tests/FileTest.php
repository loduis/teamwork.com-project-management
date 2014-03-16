<?php

class FileTest extends TestCase
{
    private $model;
    private $id;
    private $projectId;

    public function setUp()
    {
        parent::setUp();
        $this->model     = TeamWorkPm\Factory::build('file');
        $this->projectId = get_first_project_id();
        $this->id        = get_first_file_id($this->projectId);
    }

    /**
     * @test
     */
    public function upload()
    {
        try {
            $filename = 'back_file_path';
            $this->model->upload($filename);
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertEquals('Not file exist ' . $filename, $e->getMessage());
        }
        try {
            $filename = __DIR__ . '/uploads/teamworkpm.jpg';
            $this->assertNotEmpty($this->model->upload($filename));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $filename, $e->getMessage());
        }
    }


    /**
     * @test
     */
    public function save()
    {
        $data = [
            'description'=> 'Bla, Bla, Bla'
        ];
        try {
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Required field project_id', $e->getMessage());
        }
        try {
            $data['project_id'] = $this->projectId;
            $this->model->save($data);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals(
                'Required field pending_file_ref or filename',
                $e->getMessage()
            );
        }
        try {
            $data['filename'] = __DIR__ . '/uploads/teamworkpm.jpg';
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends save
     * @test
     */
    public function get()
    {
        try {
            $this->model->get(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param id', $e->getMessage());
        }
        try {
            $file = $this->model->get($this->id);
            $this->assertEquals($this->id, $file->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends save
     * @test
     */
    public function getByProject()
    {
        try {
            $this->model->getByProject(0);
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $e) {
            $this->assertEquals('Invalid param project_id', $e->getMessage());
        }
        try {
            $files = $this->model->getByProject($this->projectId);
            $this->assertGreaterThan(0, count($files));
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}