<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Defines admin operations to be performed on a narrative.
 */
class Admin_Comment extends YD_Controller
{
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('comment_flag_model');
    $this->load->model('comment_model');
  }

  /**
   * The default method called, if none is provided.
   */
  public function index($narrative_id = NULL)
  {
    $this->require_login();

    $comments = $this->comment_model->get_all($narrative_id);
    foreach ($comments as &$comment)
    {
      $flags = $this->comment_flag_model->get_by_comment_id($comment['comment_id']);
      $comment['flags'] = count($flags);
    }

    function commentsFlagSort($item1,$item2)
    {
        if ($item1['flags'] == $item2['flags']) return 0;
        return ($item1['flags'] < $item2['flags']) ? 1 : -1;
    }
    usort($comments,'commentsFlagSort');

    $data = array('comments' => $comments);
    $this->view_wrapper('admin/comments/list', $data);
  }

  /**
   * TODO: decide if needed or not
   */
  public function edit($comment_id = NULL)
  {
  }

  /**
   * Review comments includes flags and change publish status.
   */
  public function review($comment_id = 0)
  {
    $comment = $this->comment_model->get($comment_id);
    $flags = $this->comment_flag_model->get_by_comment_id($comment_id);
    $this->view_wrapper('admin/comments/review', array('comment' => $comment, 'flags' => $flags));
  }

  public function delete($comment_id = NULL)
  {
  }

}
