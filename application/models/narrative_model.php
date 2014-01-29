<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class narrative_model extends CI_Model {
  private $table = 'narratives';
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Retrieve a narrative data structure by ID, or FALSE upon failure.
   */
  public function get($narrative_id) {
    $query = $this->db->get_where($this->table, array('narrative_id' => $narrative_id));
    $narrative = $query->row_array();
    return $narrative;
  }

  /**
   * Retrieve a narrative data structure by ID, or FALSE upon failure.
   */
  public function get_all($sortby = 'id') {
    // Get the sort column
    $sort_cols = array(
      'id' => 'narrative_id',
      'age' => 'created',
      'agrees' => 'agrees',
      'disagrees' => 'disagrees',
    );
    if (!isset($sort_cols[$sortby])) {
      // TODO: Error handling
      return array();
    }
    $sort_col = $sort_cols[$sortby];


    $query = $this->db->from($this->table)
      ->order_by($sort_col, 'desc')
      ->get();
    $narratives = $query->result_array();
    return $narratives;
  }

  /**
   * Inserts a narrative structure into the database.
   */
  public function insert($narrative) {
    $this->db->insert($this->table, $narrative);
  }

  /**
   * Deletes an narrative based on the conditions passed.
   *
   * Example:
   *   $this->narrative_model->delete(array('narrative_id' => $narrative->id));
   */
  public function delete($conditions) {
    $this->db->delete($this->table, $conditions);
  }
}
