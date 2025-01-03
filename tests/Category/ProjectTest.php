<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

final class ProjectTest extends TestCase
{

    /**
     * @test
     */
    public function getAll()
    {
        $categories = $this->factory('category.project')->all();
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get(): void
    {
        $category = $this->factory('category.project')->get(47766);
        $this->assertEquals('category test', $category->name);
    }
}
