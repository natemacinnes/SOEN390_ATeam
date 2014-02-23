<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The narrative player & related operations.
 */
class tutorialPlayer extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('tutorial_model');
	}

	public function index($language){
		$row = $this->tutorial_model->get_all();
	
		$data['enurl'] = $row[0]['url'];//direct mappings are bad kids
		$data['frurl'] = $row[1]['url'];//TODO:Make a bit more generic in sprint 3
	
		$this->load->view('embedded/tutorial', $data);
	}

}