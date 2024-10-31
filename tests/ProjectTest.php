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
        $this->assertEquals(10, $this->factory('project', [
            'POST /projects' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save($data));
    }

    /**
     * @dataProvider invalidProjectProvider
     * @test
     */
    public function insertProjectInvalidData(array $data): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Required field name');
        $this->factory('project')->save($data);
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
        $this->assertTrue($this->factory('project', [
            'PUT /projects/10' => fn($data) => $this->assertMatchesJsonSnapshot($data)
        ])->save($data));
    }

    /**
     * @depends insertProject
     * @test
     */
    public function get(): void
    {
        $this->assertEquals(
            "Colombia",
            $this->factory('project', [
                'GET /projects/' . TPM_PROJECT_ID => true
            ])->get(TPM_PROJECT_ID)->name
        );
    }

    /**
     * @test
     */
    public function getAllProjects(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('project', [
                'GET /projects?status=ALL' => true
            ])->all())
        );
    }

    /**
     * @test
     */
    public function getActiveProjects(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('project', [
                'GET /projects?status=ACTIVE' => true
            ])->getActive())
        );
    }

    /**
     * @test
     */
    public function getByCompany(): void
    {
        $this->assertGreaterThan(
            0,
            count($this->factory('project', [
                'GET /companies/'. TPM_COMPANY_ID . '/projects' => true
            ])->getByCompany(TPM_COMPANY_ID))
        );
    }

    /**
     * @test
     */
    public function getArchivedProjects(): void
    {
        $this->assertGreaterThan(0,
            count($this->factory('project', [
                'GET /projects?status=ARCHIVED' => true
            ])->getArchived())
        );
    }

    /**
     * @test
     */
    public function getRates(): void
    {
        $this->assertArrayHasKey('users',
            $this->factory('project.rate', [
                'GET /projects/' . TPM_PROJECT_ID . '/rates' => true
            ])->get(TPM_PROJECT_ID)
        );
    }

        /**
     * @test
     */
    public function getStats(): void
    {
        $this->assertEquals(1,
            $this->factory('project', [
                'GET /projects/' . TPM_PROJECT_ID . '/stats' => true
            ])->getStats(TPM_PROJECT_ID)->tasks->active
        );
    }

    /**
     * @test
     */
    public function setRates(): void
    {
        // TODO this method fail on live when users params is set
        $this->assertTrue(
            $this->factory('project.rate', [
                'POST /projects/'. TPM_PROJECT_ID .'/rates'  => fn($data) => $this->assertMatchesJsonSnapshot($data)
            ])->set(TPM_PROJECT_ID, [
                'project_default' => 1,
                'users' => [
                    TPM_USER_ID => 5
                ]
            ])
        );
    }


    /**
     * @test
     */
    public function starProject(): void
    {
        $this->assertTrue($this->factory('project', [
            'PUT /projects/' . TPM_PROJECT_ID . '/star' => true
        ])->star(TPM_PROJECT_ID));
    }

    /**
     * @test
     */
    public function unStarProject(): void
    {
        $this->assertTrue($this->factory('project', [
            'PUT /projects/' . TPM_PROJECT_ID . '/unstar' => true
        ])->unStar(TPM_PROJECT_ID));
    }

    /**
     * @dataProvider invalidIdProvider
     * @test
     */
    public function starProjectWithInvalidId(int $id): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid param id');
        $this->factory('project')->star($id);
    }

    /**
     * @test
     *
     * @return void
     */
    public function delete()
    {
        $this->assertTrue($this->factory('project', [
            'DELETE /projects/' . TPM_PROJECT_ID => true
        ])->delete(TPM_PROJECT_ID));
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
