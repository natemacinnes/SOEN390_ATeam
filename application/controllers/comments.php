<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('comment_flag_model');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 *    http://example.com/index.php/welcome
	 *  - or -
	 *    http://example.com/index.php/welcome/index
	 *  - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($narrative_id = 1)
	{
		$comments = $this->commenting_model->get_all_non_parent($narrative_id);
		//$this->view_wrapper('pages/comments', $data);
		//Not sure if view_wrapper will cause errors so I commented it out for now
		$data = array('comments' => $comments, 'narrative_id' => $narrative_id);
		$this->view_wrapper('embedded/comments', $data);
	}

	public function gui($narrative_id = 1)
	{
		$comments = $this->commenting_model->get_all_non_parent($narrative_id);
		//$this->view_wrapper('pages/comments', $data);
		//Not sure if view_wrapper will cause errors so I commented it out for now
		$data = array('comments' => $comments, 'narrative_id' => $narrative_id);
		$this->view_wrapper('pages/comments', $data);
	}

	public function post_comment($narrative_id, $parent_comment, $time_created, $body_of_text)
	{
		$this->commenting_model->add_comment_to_database($narrative_id, $parent_comment, $time_created, $body_of_text);
	}

	public function flag_comment($comment_id)
	{
		$this->comment_flag_model->insert($comment_id);
	}

	public function reply_to_comment($narrative_id, $comment_id, $time_created, $body_of_text)
	{
		$this->commenting_model->add_comment_with_parent_to_database($narrative_id, $comment_id, $time_created, $body_of_text);
	}
}
