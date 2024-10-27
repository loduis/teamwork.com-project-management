<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

final class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->factory('category.message')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get(): void
    {
        $category = $this->factory('category.message')->get(698387);
        $this->assertEquals('category test', $category->name);
    }
}
