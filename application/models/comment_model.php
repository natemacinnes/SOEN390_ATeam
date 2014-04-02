<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model extends CI_Model {
  private $table = 'comments';
  public function __construct()
  {
    parent::__construct();
  }


  /**
   * Retrieve get all comments for for a given narrative (used for admin side)
   */
  public function get_all($narrative_id = NULL, $sort_by = 'id', $sort_order = 'asc', $offset = 0, $limit = NULL)
  {
    // Get the sort column
    $sort_cols = array(
      'id' => 'comment_id',
      'parent' => 'parent_comment',
      'narrative' => 'narrative_id',
      'created' => 'created',
      'status' => 'status',
      'flags' => 'flags',
    );
    if (!isset($sort_cols[$sort_by]))
    {
      // TODO: Error handling
      return array();
    }
    $sort_col = $sort_cols[$sort_by];

    $query = $this->db->from($this->table);
    if($narrative_id)
    {
      $this->db->where('narrative_id', $narrative_id);
    }
    if ($limit)
    {
      $query->limit($limit, $offset);
    }
    $query = $this->db
      ->order_by($sort_col, $sort_order)
      ->get();
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
    foreach ($query->result_array() as $comment)
    {
      $comments[$comment['comment_id']] = $comment;
    }
    return $comments;
  }

  /**
   * Returns the number of records.
   */
  public function get_total_count()
  {
    $query = $this->db->query('SELECT count(*) as count FROM ' . $this->table);
    $row = $query->row_array();
    return $row['count'];
  }

  /**
   * Inserts a narrative structure into the database.
   */
  public function insert($comment)
  {
    $this->db->insert($this->table, $comment);
    return $this->db->insert_id();
  }

  /**
   * Deletes an comment based on the conditions passed.
   *
   * Example:
   *   $this->comment_model->delete(array('comment_id' => $comment['comment_id']));
   */
  public function delete($conditions)
  {
    $this->db->delete($this->table, $conditions);
  }

  /**
   * Increment comment flag count
   */
  function flag($comment_id)
  {
    $this->db->where('comment_id', $comment_id);
    $this->db->set('flags', 'flags+1', FALSE);
    $this->db->update('comments');
  }

  /**
   * Dismisses all flags set on the comment
   */
  function dismiss_flags($comment_id)
  {
    $this->db->where('comment_id', $comment_id);
    $this->db->set('flags', 0, FALSE);
    $this->db->update('comments');
  }
}
