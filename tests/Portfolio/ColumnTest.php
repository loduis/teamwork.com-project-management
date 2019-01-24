<?php

class ColumnTest extends TestCase
{
    /** @var \TeamWorkPm\Portfolio\Column */
    private $model;

    /** @var integer */
    private $boardId;

    /** @var integer */
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('portfolio/column');
        $this->boardId = get_first_portfolio_board_id();
        $this->id = get_first_portfolio_board_column_id($this->boardId);
    }

    /**
     * @param $data
     *
     * @dataProvider provider
     * @test
     */
    public function insert($data)
    {
        try {
            $data['board_id'] = $this->boardId;

            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);

            $portfolioColumn = $this->model->get($id);

            // If the name is already taken, TW adds a number suffix
            $this->assertStringStartsWith($data['name'], $portfolioColumn->name);

            $this->assertEquals($data['color'], $portfolioColumn->color);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @param $data
     *
     * @dataProvider provider
     * @test
     */
    public function update($data)
    {
        try {
            $data['id'] = $this->id;
            $data['color'] = '#ffffff';

            $this->assertTrue($this->model->save($data));

            $portfolioColumn = $this->model->get($this->id);
            $this->assertEquals($data['color'], $portfolioColumn->color);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function get()
    {
        try {
            $portfolioColumn = $this->model->get($this->id);
            $this->assertEquals($this->id, $portfolioColumn->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAll()
    {
        try {
            $portfolioColumns = $this->model->getAll($this->boardId);

            $this->assertEquals($this->id, $portfolioColumns[0]->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException \TeamWorkPm\Exception
     * @test
     */
    public function getAllInvalidId()
    {
        $this->model->getAll(0);
    }

    /**
     * @expectedException \TeamWorkPm\Exception
     * @test
     */
    public function deleteInvalidId()
    {
        $this->model->delete(0);
    }

    /**
     * @test
     */
    public function delete()
    {
        try {
            $this->assertTrue($this->model->delete($this->id));
        } catch (\TeamWorkPm\Exception $e) {
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
                    "name" => "test column",
                    "color" => "#eeeeee",
                ]
            ]
        ];
    }
}
