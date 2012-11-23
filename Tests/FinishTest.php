<?php

class FinishedTest extends TestCase
{

    /**
     *
     * @test
     */
    public function deleteCategoryProject()
    {
        try {
            $id = get_first_project_category_id();
            $category = TeamWorkPm::factory('category/project');
            $this->assertTrue($category->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function deleteProject()
    {
        try {
            $id = get_first_project_id();
            $project = TeamWorkPm::factory('project');
            $this->assertTrue($project->delete($id));
        } catch (\TeamWorkPm\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

}