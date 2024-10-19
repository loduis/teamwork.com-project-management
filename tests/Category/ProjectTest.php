<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

class ProjectTest extends TestCase
{

    /**
     * @test
     */
    public function getAll()
    {
        $categories = $this->tpm('category.project')->all();
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get()
    {
        $category = $this->tpm('category.project')->get(47766);
        $this->assertEquals('category test', $category->name);
    }
}
