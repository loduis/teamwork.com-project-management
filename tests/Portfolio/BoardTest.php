<?php

class BoardTest extends TestCase
{
    /** @var \TeamWorkPm\Portfolio\Board */
    private $model;

    /** @var int */
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = TeamWorkPm\Factory::build('portfolio/board');
        $this->id = get_first_portfolio_board_id();
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
            $id = $this->model->save($data);
            $this->assertGreaterThan(0, $id);

            $portfolioBoard = $this->model->get($id);

            // If the name is already taken, TW adds a number suffix
            $this->assertStringStartsWith($data['name'], $portfolioBoard->name);

            $this->assertEquals($data['color'], $portfolioBoard->color);
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

            $portfolioBoard = $this->model->get($this->id);
            $this->assertEquals($data['color'], $portfolioBoard->color);
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
            $portfolioBoard = $this->model->get($this->id);
            $this->assertEquals($this->id, $portfolioBoard->id);
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
            $portfolioBoards = $this->model->getAll();

            $this->assertEquals($this->id, $portfolioBoards[0]->id);
        } catch (\TeamWorkPm\Exception $e) {
            $this->fail($e->getMessage());
        }
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
                    'name' => 'test board',
                    'color' => '#cccccc'
                ]
            ]
        ];
    }
}
