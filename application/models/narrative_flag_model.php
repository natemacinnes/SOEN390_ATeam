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

  /**
  * Flag the narrative
  */
  public function insert($narrative_id, $text)
  {
    //update flags on a narrative
    $this->db->where('narrative_id', $narrative_id);
    $this->db->set('flags', 'flags+1', FALSE);
    $this->db->update('narratives');

    //insert flag description into narrative flag table
    $data = array(
        'narrative_id' => $narrative_id,
        'description' => $text
        );
    $this->db->insert('narrative_flags', $data);
    /*$query = $this->db->query('SELECT * FROM narratives WHERE narrative_id=' . $narrative_id . ';');
    $row = $query->row_array();
    $newFlag = $row['flags'] + 1;

    $this->db->query('UPDATE narratives SET flags=' . $newFlag . " WHERE narrative_id=" . $narrative_id . ";");*/
  }
}
