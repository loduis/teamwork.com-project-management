<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->tpm('category.message')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get()
    {
        $category = $this->tpm('category.message')->get(698387);
        $this->assertEquals('category test', $category->name);
    }
}
