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
		$this->load->model('commenting_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index($narrative_id)
	{
		$narrative = $this->narrative_model->get($narrative_id);
		$data = array('narrative_id' => $narrative_id, 'narrative' => $narrative);
		$this->load->view('embedded/player', $data);
		$comments = $this->commenting_model->get_all_non_parent($narrative_id);
		//$this->view_wrapper('pages/comments', $data);
		//Not sure if view_wrapper will cause errors so I commented it out for now
		$data = array('comments' => $comments, 'narrative_id' => $narrative_id);
		$this->load->view('embedded/comments', $data);
	}
}
