<?php

namespace TeamWorkPm\Tests;

use TeamWorkPm\Exception;

final class ProjectTest extends TestCase
{
    /**
     * @dataProvider projectProvider
     * @test
     */
    public function insertProject(array $data): void
    {
        $this->assertEquals(10, $this->postTpm('project', function ($headers) {
            $project = $headers['X-Params'];
            $this->assertObjectHasProperty('name', $project);
            $this->assertObjectHasProperty('description', $project);
        })->save($data));
    }

    /**
     * @dataProvider invalidProjectProvider
     * @test
     */
    public function insertProjectInvalidData(array $data): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Required field name');
        $this->postTpm('project')->save($data);
    }

    /**
     * @depends insertProject
     * @dataProvider projectProvider
     * @test
     */
    public function updateProject(array $data): void
    {
        $data['id'] = 10; // Assume the ID of the project to update is 10
        $data['name'] = 'Updated Project Name';
        $data['custom_fields'] = [
            62435 => 'This is a test'
        ];
        $this->assertTrue($this->putTpm('project', function ($headers) {
            $project = $headers['X-Params'];
            $this->assertEquals('Updated Project Name', $project->name);
            $this->assertObjectHasProperty('customFields', $project);
            $this->assertCount(1, $project->customFields);
            $this->assertObjectHasProperty('customFieldId', $project->customFields[0]);
            $this->assertObjectHasProperty('value', $project->customFields[0]);
        })->save($data));
    }

    /**
     * @depends insertProject
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "Colombia",
            $this->getTpm('project')->get(967489)->name
        );
    }

    /**
     * @test
     */
    public function getAllProjects(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('project')->all()));
    }

    /**
     * @test
     */
    public function getActiveProjects(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('project')->getActive()));
    }

    /**
     * @test
     */
    public function getArchivedProjects(): void
    {
        $this->assertGreaterThan(0, count($this->getTpm('project')->getArchived()));
    }

    /**
     * @test
     */
    public function getRates(): void
    {
        $rates = $this->getTpm('project')->getRates(967489);
        $this->assertTrue(isset($rates->users));
    }


    /**
     * @test
     */
    public function starProject(): void
    {
        $this->assertTrue($this->putTpm('project')->star(10)); // Assume 10 is a valid project ID
    }

    /**
     * @test
     */
    public function unStarProject(): void
    {
        $this->assertTrue($this->putTpm('project')->unStar(10)); // Assume 10 is a valid project ID
    }

    /**
     * @dataProvider invalidIdProvider
     * @test
     */
    public function starProjectWithInvalidId(int $id): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->getTpm('project')->star($id);
    }

    public function projectProvider(): array
    {
        return [
            [
                [
                    'name' => 'Test Project',
                    'description' => 'Description of Test Project',
                    'use_tasks' => true,
                    'start_date' => '2024-01-01',
                    'end_date' => '2024-12-31',
                ],
            ],
        ];
    }

    public function invalidProjectProvider(): array
    {
        return [
            [
                [
                    'description' => 'Description without a name',
                    'use_tasks' => true,
                ],
            ],
            [
                [
                    'name' => '',
                    'description' => 'Invalid project with empty name',
                ],
            ],
        ];
    }

    public function invalidIdProvider(): array
    {
        return [
            [-1],
            [0]
        ];
    }
}
