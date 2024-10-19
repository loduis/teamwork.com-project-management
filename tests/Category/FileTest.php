<?php

namespace TeamWorkPm\Tests\Category;

use TeamWorkPm\Category\File;
use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * @test
     */
    public function getByProject()
    {
        $categories = $this->tpm('category.file')->getByProject(967489);
        $this->assertCount(1, $categories);
        $this->assertEquals('category test', $categories[0]->name);
    }

    /**
     * @test
     */
    public function get()
    {
        $category = $this->tpm('category.file')->get(1634602);
        $this->assertEquals('category test', $category->name);
    }
}
