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
		if (strlen($text))
		{
			$comment = array(
		      'narrative_id' => $narrative_id,
		      'parent_comment' => $parent_id,
		      'body' => $text,
		      'status' => 1,
		    );
			$comment['comment_id'] = $this->comment_model->insert($comment);
			$comment['created'] = date("Y-m-d h:i:s");
			$comments = $this->comment_model->get_by_narrative_id($narrative_id);

			$this->load->view('embedded/comment', array('comment' => $comment, 'comments' => $comments));
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

	public function reply_form($parent_id = NULL, $body = NULL)
	{
		$this->load->view('embedded/reply', array( 'parent_id' => $parent_id, 'parent_body' => $body));
	}
}
