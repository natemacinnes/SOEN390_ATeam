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
	 * Review comments includes flags and change publish status.
	 */
	public function review($comment_id = 0)
	{
		$comment = $this->comment_model->get($comment_id);
		$flags = $this->comment_flag_model->get_by_comment_id($comment_id);
		$this->view_wrapper('admin/comments/review', array('comment' => $comment, 'flags' => $flags));
	}

	public function delete($comment_id)
	{
		$comment = $this->comment_model->delete(array('comment_id' => $comment_id));
		redirect('admin/comments');
	}
}
