<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model extends CI_Model {
  private $table = 'comments';
  public function __construct() {
    parent::__construct();
  }


  /**
   * Retrieve get all comments for for a given narrative
   */
  public function get_all()
  {
    $query = $this->db->from($this->table)->get();
    $narratives = $query->result_array();
    return $narratives;
  }

  /**
   * Retrieve a comments by its ID.
   */
  public function get($comment_id)
  {
    $query = $this->db->get_where($this->table, array('comment_id' => $comment_id));
    $comment = $query->row_array();
    return $comment;
  }

  /**
   * Retrieve comments for a given narrative
   */
  public function get_by_narrative_id($narrative_id)
  {
    $this->db->from($this->table);
    $this->db->where('narrative_id', $narrative_id);
    $query = $this->db->get();
    $comments = array();
    foreach ($query->result_array() as $comment) {
      $comments[$comment['comment_id']] = $comment;
    }
    return $comments;
  }

  public function insert($narrative_id, $body_of_text, $parent_id = NULL)
  {
    $data = array(
      'narrative_id' => $narrative_id,
      'parent_comment' => $parent_id,
      'body' => $body_of_text,
      'status' => 1,
    );
    $this->db->insert('comments', $data);
  }
}
