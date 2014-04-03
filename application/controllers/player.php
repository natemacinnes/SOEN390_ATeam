<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The narrative player & related operations.
 */
class Player extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('narrative_model');
		$this->load->model('comment_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index($narrative_id)
	{
		$narrative = $this->narrative_model->get($narrative_id);
		$data = array('narrative_id' => $narrative_id, 'narrative' => $narrative);
		$this->load->view('embedded/player', $data);
		$comments = $this->comment_model->get_by_narrative_id($narrative_id);

		$rendered_comments = '';
		$comments_copy = $comments;

		$comments_countdown = 25;
		foreach ($comments as $i => $comment)
		{
			$comments_countdown--;
			if ($comments_countdown < 0) {
				break;
			}

			// Render the comments into the variable
			$rendered_comments .= $this->load->view('embedded/comment', array('comment' => $comment, 'comments' => $comments_copy), TRUE);
		}
		$data = array('comments' => $rendered_comments, 'narrative_id' => $narrative_id);
		$this->load->view('embedded/comments', $data);
	}

	public function flag($narrative_id)
	{
		$this->narrative_model->flag($narrative_id);
	}
}
