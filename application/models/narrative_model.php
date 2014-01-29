<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class narrative_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Retrieve a narrative data structure by ID, or FALSE upon failure.
   */
  public function get($narrative_id) {
    $query = $this->db->get_where('narratives', array('narrative_id' => $narrative_id));
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


    $query = $this->db->from('narratives')
      ->order_by($sort_col, 'desc')
      ->get();
    $narratives = $query->result_array();
    return $narratives;
  }
}
