<?php

namespace TeamWorkPm\Tests\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Portfolio\Column;
use TeamWorkPm\Tests\TestCase;

class ColumnTest extends TestCase
{
    /** @var Column */
    private $model;

    /** @var int */
    private $boardId;

    /** @var int */
    private $id;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = Factory::build('portfolio/column');
        $this->boardId = get_first_portfolio_board_id();
        $this->id = get_first_portfolio_board_column_id($this->boardId);
    }

    /**
     * @param $data
     *
     * @dataProvider provider
     * @test
     */
    public function insert($data): void
    {
        try {
            $data['board_id'] = $this->boardId;

            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);

            $portfolioColumn = $this->model->get($id);

            // If the name is already taken, TW adds a number suffix
            $this->assertStringStartsWith($data['name'], $portfolioColumn->name);

            $this->assertEquals($data['color'], $portfolioColumn->color);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @param $data
     *
     * @dataProvider provider
     * @test
     */
    public function update($data): void
    {
        try {
            $data['id'] = $this->id;
            $data['color'] = '#ffffff';

            $this->assertTrue($this->model->save($data));

            $portfolioColumn = $this->model->get($this->id);
            $this->assertEquals($data['color'], $portfolioColumn->color);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get(): void
    {
        try {
            $portfolioColumn = $this->model->get($this->id);
            $this->assertEquals($this->id, $portfolioColumn->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAllForBoard(): void
    {
        try {
            $portfolioColumns = $this->model->getAllForBoard($this->boardId);

            $this->assertEquals($this->id, $portfolioColumns[0]->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAllForBoardInvalidId(): void
    {
        $this->expectException(\Exception::class);
        $this->model->getAllForBoard(0);
    }

    /**
     * @test
     */
    public function deleteInvalidId(): void
    {
        $this->expectException(\Exception::class);
        $this->model->delete(0);
    }

    /**
     * @test
     */
    public function delete(): void
    {
        try {
            $this->assertTrue($this->model->delete($this->id));
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * Data Provider
     *
     * @return array
     */
    public function provider()
    {
        return [
            [
                [
                    'name' => 'test column',
                    'color' => '#eeeeee',
                ],
            ],
        ];
    }
}
