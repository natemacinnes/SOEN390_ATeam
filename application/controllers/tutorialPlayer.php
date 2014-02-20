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
    	
		$row = $this->tutorial_model->get_by_language($language);
		//die(print_r($row,TRUE));
		$data = array('url' => $row['url']);
		$this->load->view('embedded/tutorial', $data);
	}

}