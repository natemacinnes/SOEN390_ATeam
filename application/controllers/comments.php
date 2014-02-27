<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('comment_model');
		$this->load->model('comment_flag_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		return;
	}

	/**
	 * Stores a reply to a comment (submitted via AJAX POST) and returns the
	 * rendered comment.
	 */
	public function reply($narrative_id, $parent_id = NULL)
	{
		$text = $this->input->post('comment-text');
		if (strlen($text)) {
			$comment = array(
	      'narrative_id' => $narrative_id,
	      'parent_comment' => $parent_id,
	      'body' => $text,
	      'status' => 1,
	    );
			$comment['comment_id'] = $this->comment_model->insert($comment);
			$this->load->view('embedded/comment', array('comment' => $comment));
		}
		else {
			// Set header: 400 Bad response
			$this->output->set_status_header('400');
		}
	}

	/**
	 * Store a flag on a comment.
	 */
	public function flag($comment_id)
	{
		$text = $this->input->post('comment-text');
		$this->comment_flag_model->insert($comment_id, $text);
	}
}
