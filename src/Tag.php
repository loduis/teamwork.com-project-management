<?php namespace TeamWorkPm;

class Tag extends Model
{
  protected function init()
  {
    $this->fields = [
      'name'=>true,
      'color'=>[
        'required'=>true,
        'attributes'=>[
          'type'=>'string'
        ]
      ],
    ];
    $this->action = 'tags';
  }

  public function get($id = null)
  {
    $id = (int) $id;
    if ($id <= 0) {
      throw new Exception('Invalid param id');
    }
    return $this->rest->get("$this->action/$id");
  }

  /**
   * Retrieve All Tags
   * GET /tags
   * All of the available tags are returned for all projects
   *
   * @param null $tag_name
   * @return TeamWorkPm\Response\Model
   *
   * @author Jamie Buckell
   */
  public function getAllTags($tag_name = null)
  {
    $params = [];
    if (!empty($tag_name)) {
      $params = [
        'name' => $tag_name
      ];
    }
    return $this->rest->get("$this->action", $params);
  }

  /**
   * Creating a Tag
   *
   * POST /tags.xml
   *
   * Creates a new tag.
   *
   * @param array $data
   * @return int
   */
  public function insert(array $data)
  {
    return $this->rest->post("$this->action", $data);
  }
}