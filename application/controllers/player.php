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
	}

	/**
   * The default method called, if none is provided.
   */
	public function index($narrative_id)
	{
		$narrative = $this->narrative_model->get($narrative_id);
		$data = array('narrative_id' => $narrative_id, 'narrative' => $narrative);
		$this->load->view('embedded/player', $data);
	}
}
