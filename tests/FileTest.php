<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;

final class FileTest extends TestCase
{
    /**
     * @test
     */
    public function upload(): void
    {
        try {
            $filename = 'back_file_path';
            $this->factory('file')->upload($filename);
        } catch (Exception $e) {
            $this->assertEquals('Not file exist ' . $filename, $e->getMessage());
        }
        //
        $filename = __DIR__ . '/uploads/teamworkpm.jpg';
        $files = $this->factory('file', [
            'POST /pendingfiles' => function ($params, $headers) {
                $this->assertInstanceOf('CURLFile', $headers['X-Params']['file']);
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            }
        ])->upload($filename);

        $this->assertIsArray($files);
        $this->assertCount(1, $files);
    }

    /**
     * @test
     */
    public function addToProject(): void
    {
        $res = $this->factory('file', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /projects/967489/files' => function($data) {
                $this->assertMatchesJsonSnapshot($data);
            }
        ])->add([
            'project_id' => TPM_PROJECT_ID_1,
            'files' => [
                __DIR__ . '/uploads/person.png',
                __DIR__ . '/uploads/teamworkpm.jpg'
            ]
        ]);
        $this->assertEquals(TPM_TEST_ID, $res);
    }

    /**
     * @test
     */
    public function addNewVersion(): void
    {
        $res = $this->factory('file', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /files/' . TPM_FILE_ID  => true
        ])->add([
            'id' => TPM_FILE_ID,
            'files' => [
                __DIR__ . '/uploads/person.png',
            ]
        ]);
        $this->assertEquals(TPM_TEST_ID, $res);

        $res = $this->factory('file', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /files/' . TPM_FILE_ID  => true
        ])->add([
            'id' => TPM_FILE_ID,
            'files' => __DIR__ . '/uploads/person.png',
        ]);
        $this->assertEquals(TPM_TEST_ID, $res);
    }

    /**
     * @test
     */
    public function addToTask(): void
    {
        $res = $this->factory('file', [
            'POST /pendingfiles' => function () {
                return '{"pendingFile":{"ref":"tf_3d9cfae3-65f7-4ff8-8bf5-ca0512de600a"}}';
            },
            'POST /tasks/'. TPM_TASK_ID . '/files' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->add([
            'task_id' => TPM_TASK_ID,
            'files' => [
                __DIR__ . '/uploads/person.png',
                __DIR__ . '/uploads/teamworkpm.jpg'
            ]
        ]);
        $this->assertEquals(TPM_TEST_ID, $res);
    }

    /**
     * @test
     */
    public function all()
    {
        $files = $this->factory('file')->all();

        $this->assertCount(2, $files);

        $this->assertEquals('teamworkpm', $files[0]->fileSource);
    }

    /**
     * @test
     */
    public function get()
    {
        $this->assertEquals('person.png', $this->factory('file')->get(TPM_FILE_ID)->originalName);
    }

    /**
     * @depends addToProject
     * @test
     */
    public function getByProject(): void
    {
        $this->assertCount(1, $this->factory('file', [
            'GET /projects/' . TPM_PROJECT_ID_1 . '/files' => true
        ])->getByProject(TPM_PROJECT_ID_1));
    }

    /**
     * @depends addToTask
     * @test
     */
    public function getByTask(): void
    {
        $this->assertCount(1, $this->factory('file', [
            'GET /tasks/' . TPM_TASK_ID . '/files' => true
        ])->getByTask(TPM_TASK_ID));
    }

    /**
     * @test
     */
    public function move(): void
    {
        $this->assertTrue($this->factory('file', [
            'PUT /files/' . TPM_FILE_ID . '/move' => true
        ])->move(TPM_FILE_ID, TPM_PROJECT_ID_1));
    }

    /**
     * @test
     */
    public function copy(): void
    {
        $this->assertEquals(TPM_TEST_ID, $this->factory('file', [
            'PUT /files/' . TPM_FILE_ID . '/copy' =>   function () {
                return '{"STATUS":"OK","id": ' . TPM_TEST_ID . '}';
            }
        ])->copy(TPM_FILE_ID, TPM_PROJECT_ID_1));
    }

    /**
     * @test
     *
     */
    public function delete(): void
    {
        $this->assertTrue($this->factory('file', [
            'DELETE /files/' . TPM_FILE_ID => true
        ])->delete(TPM_FILE_ID));
    }
}
