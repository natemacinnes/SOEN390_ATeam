<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Used for providing static/view-only pages.
 */
class Pages extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('topic_model');
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		$topic = $this->topic_model->get_topic();
		$data = array('topic' => $topic);
		$this->view_wrapper('pages/home', $data);
	}
}
