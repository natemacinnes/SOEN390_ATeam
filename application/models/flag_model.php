<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flag_Model extends CI_Model {
  private $table = 'flags';
  public function __construct() {
    parent::__construct();
  }


  /**
   * Retrieve flags for a given narrative
   */
  public function get_by_narrative($narrative_id) {



    $query = $this->db->from($this->table);
    $this->db->where('narrative_id', $narrative_id);

    $query = $this->db->get();

    $flags = $query->result_array();
    return $flags;
  }
}
