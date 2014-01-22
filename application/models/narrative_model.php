<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class narrative_model extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  /**
   * Retrieve a narrative object by ID, or FALSE upon failure.
   */
  public function get($narrative_id) {
    return FALSE;
    $query = $this->db->get_where('narratives', array('id' => $narrative_id));
    $narrative = $query->row_object();
    return $narrative;
  }
}
