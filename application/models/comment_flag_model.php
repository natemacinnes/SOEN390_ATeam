<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Flag_Model extends CI_Model
{
  private $table = 'comments';
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieve flags for a given narrative
   */
  public function get_by_comment_id($comment_id)
  {
    $this->db->from($this->table);
    $this->db->where('comment_id', $comment_id);

    $query = $this->db->get();

    return $query->result_array();
  }

  public function insert($comment_id)
  {
    $this->db->where('comment_id', $comment_id);
    $this->db->set('flags', 'flags+1', FALSE);
    $this->db->update('comments');

    return $this->db->insert_id();
  }
}
