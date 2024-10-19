<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Tests\TestCase;

final class LinkTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->tpm('category.link')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get(): void
    {
        $category = $this->tpm('category.link')->get(337016);
        $this->assertEquals('category test', $category->name);
    }
}
