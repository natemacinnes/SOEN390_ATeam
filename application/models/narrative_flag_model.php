<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narrative_Flag_Model extends CI_Model {
  private $table = 'narrative_flags';
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieve flags for a given narrative
   */
  public function get_by_narrative_id($narrative_id)
  {
    $this->db->from($this->table);
    $this->db->where('narrative_id', $narrative_id);

    $query = $this->db->get();

    return $query->result_array();
  }
}
