<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model extends CI_Model {
  private $table = 'comments';
  public function __construct() {
    parent::__construct();
  }


  /**
   * Retrieve flags for a given narrative
   */
  public function get_all($narrative_id = NULL, $sortby = 'id')
  {
    // Get the sort column
    $sort_cols = array(
      'id' => 'narrative_id',
      'age' => 'created',
      'agrees' => 'agrees',
      'disagrees' => 'disagrees',
    );

    if (!isset($sort_cols[$sortby]))
    {
      // TODO: Error handling
      return array();
    }

    $sort_col = $sort_cols[$sortby];


    $query = $this->db->from($this->table);
    if ($narrative_id)
    {
      $this->db->where('narrative_id', $narrative_id);
    }
    $query = $this->db
      ->order_by($sort_col, 'asc')
      ->get();
    $narratives = $query->result_array();
    return $narratives;
  }
}
