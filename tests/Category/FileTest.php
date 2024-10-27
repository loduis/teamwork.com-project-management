<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

final class FileTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->factory('category.file')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get(): void
    {
        $category = $this->factory('category.file')->get(1634602);
        $this->assertEquals('category test', $category->name);
    }
}
