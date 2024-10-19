<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

final class NotebookTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->tpm('category.notebook')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get(): void
    {
        $category = $this->tpm('category.notebook')->get(1037083);
        $this->assertEquals('category test', $category->name);
    }
}
