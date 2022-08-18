<?php

namespace TeamWorkPm\Tests\Portfolio;

use TeamWorkPm\Exception;
use TeamWorkPm\Factory;
use TeamWorkPm\Portfolio\Card;
use TeamWorkPm\Tests\TestCase;

class CardTest extends TestCase
{
    /** @var Card */
    private $model;

    /** @var int */
    private $projectId;

    /** @var int */
    private $boardId;

    /** @var int */
    private $columnId;

    /** @var int */
    private $id;

    public function setUp()
    {
        parent::setUp();
        $this->model = Factory::build('portfolio/card');
        $this->projectId = get_first_project_id();
        $this->boardId = get_first_portfolio_board_id();
        $this->columnId = get_first_portfolio_board_column_id($this->boardId);
        $this->id = get_first_portfolio_card_id($this->columnId);
    }

    /**
     * @test
     */
    public function insert()
    {
        try {
            $data['columnId'] = $this->columnId;
            $data['projectId'] = $this->projectId;

            $this->assertTrue($this->model->save($data));
        } catch (Exception $e) {
            $this->assertEquals('Already exists', $e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function insertInvalidColumnId()
    {
        $data['columnId'] = 0;
        $data['projectId'] = $this->projectId;

        $this->model->save($data);
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function insertInvalidProjectId()
    {
        $data['columnId'] = $this->columnId;
        $data['projectId'] = 0;

        $this->model->save($data);
    }

    /**
     * @test
     */
    public function update()
    {
        try {
            $data['id'] = $this->id;
            $data['oldColumnId'] = $this->columnId;
            $data['columnId'] = $this->columnId;

            $this->assertTrue($this->model->save($data));

            $portfolioCard = $this->model->get($this->id);

            $this->assertEquals($data['columnId'], $portfolioCard->columnId);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function updateInvalidId()
    {
        $data['id'] = 0;
        $data['oldColumnId'] = $this->columnId;
        $data['columnId'] = $this->columnId;

        $this->model->save($data);
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function updateInvalidColumnId()
    {
        $data['id'] = $this->id;
        $data['oldColumnId'] = 0;
        $data['columnId'] = $this->columnId;

        $this->model->save($data);
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function updateInvalidOldColumnId()
    {
        $data['id'] = $this->id;
        $data['oldColumnId'] = $this->columnId;
        $data['columnId'] = 0;

        $this->model->save($data);
    }

    /**
     * @test
     */
    public function get()
    {
        try {
            $portfolioCard = $this->model->get($this->id);
            $this->assertEquals($this->id, $portfolioCard->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @test
     */
    public function getAllForColumn()
    {
        try {
            $portfolioCards = $this->model->getAllForColumn($this->columnId);

            $this->assertEquals($this->id, $portfolioCards[0]->id);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @expectedException Exception
     * @test
     */
    public function getAllForColumnInvalidId()
    {
        $this->model->getAllForColumn(0);
    }

    /**
     * @expectedException Exception
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
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
