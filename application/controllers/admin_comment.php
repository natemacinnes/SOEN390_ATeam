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
		$this->load->model('comment_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		return;
	}

	/**
	 * Review comments includes flags and change publish status.
	 */
	public function review($comment_id)
	{
		$this->require_login();
		$comment = $this->comment_model->get($comment_id);
		if (!$comment) {
			show_error("The specified comment does not exist / Le commentaire indiqué n'existe pas.");
		}
		$parent_comment = array();
		if ($comment['parent_comment']) {
			$parent_comment = $this->comment_model->get($comment['parent_comment']);
		}
		$this->view_wrapper('admin/comments/review', array('comment' => $comment, 'parent_comment' => $parent_comment));
	}

	/**
	 * Deletes a comment from the database.
	 */
	public function delete($comment_id)
	{
		$this->require_login();
		$this->comment_model->delete(array('comment_id' => $comment_id));
		$this->system_message_model->set_message("The comment was deleted successfully.");
		redirect('admin/comments');
	}

	/**
	 * Removes all flags on a comment.
	 */
	public function dismiss_flags($comment_id)
	{
		$this->require_login();
		$this->comment_model->dismiss_flags($comment_id);
		$this->system_message_model->set_message("All flags on this comment were dismissed.");
		redirect('admin/comments');
	}
}
