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
		$comments = $this->comment_model->get_by_narrative_id($narrative_id);
		$data = array('comments' => $comments, 'narrative_id' => $narrative_id);
		$this->view_wrapper('embedded/comments', $data);
	}

	public function view($comment_id) {
		$comment = $this->comment_model
	}

	public function gui($narrative_id = 1)
	{
		$comments = $this->comment_model->get_by_narrative_id($narrative_id);
		$data = array('comments' => $comments, 'narrative_id' => $narrative_id);
		$this->view_wrapper('pages/comments', $data);
	}

	public function add($narrative_id, $body_of_text, $parent_id = NULL)
	{
		$comment = array(
      'narrative_id' => $narrative_id,
      'parent_comment' => $parent_id,
      'body' => $body_of_text,
      'status' => 1,
    );
		$this->comment_model->insert($comment);
	}

	public function flag($comment_id)
	{
		$this->comment_flag_model->insert($comment_id);
	}

	public function reply($narrative_id, $body_of_text, $parent_id = NULL)
	{
		$comment = array(
      'narrative_id' => $narrative_id,
      'parent_comment' => $parent_id,
      'body' => $body_of_text,
      'status' => 1,
    );
		$this->comment_model->insert($comment);
	}
}
