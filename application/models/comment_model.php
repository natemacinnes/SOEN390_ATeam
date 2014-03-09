<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model extends CI_Model {
  private $table = 'comments';
  public function __construct() {
    parent::__construct();
  }


  /**
   * Retrieve get all comments for for a given narrative (used for admin side)
   */
  public function get_all($narrative_id = NULL)
  {
    $this->db->from($this->table);
    if($narrative_id)
    {
      $this->db->where('narrative_id', $narrative_id);
    }
    $query = $this->db->get();
    $comments = $query->result_array();
    return $comments;
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
   * Retrieve comments for a given narrative (used client side)
   */
  public function get_by_narrative_id($narrative_id)
  {
    $query = $this->db->from($this->table)
      ->where('narrative_id', $narrative_id)
      ->order_by('created', 'desc')
      ->get();
    $comments = array();
    foreach ($query->result_array() as $comment) {
      $comments[$comment['comment_id']] = $comment;
    }
    return $comments;
  }

  /**
   * Inserts a narrative structure into the database.
   */
  public function insert($comment)
  {
    $comment = array(
      'narrative_id' => 1,
      'body' => "comment text here",
      'status' => 1,
    );
    $this->db->insert($this->table, $comment);
    return $this->db->insert_id();
  }
}
