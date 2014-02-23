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
    $this->load->model('narrative_model');
    $this->load->model('editing_model');
    $this->load->model('admin_model');
    $this->load->model('narrative_flag_model');
    $this->load->model('comment_model');
  }

  /**
   * The default method called, if none is provided.
   */
  public function index($narrative_id = NULL)
  {
    $this->require_login();

    $comments = $this->comment_model->get_all($narrative_id);

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
  }

  public function delete($comment_id = NULL)
  {
  }

}
