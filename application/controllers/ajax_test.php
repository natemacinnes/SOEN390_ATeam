<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_Test extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('unit_test');
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


	public function index()
	{
		$data['title'] = "Unit Tests For ajax.php";

		//narrative_model.php
		//audioImage() function
		$this->load->controller('ajax');
		$ajax_audioImage = $this->ajax->audioImage(1, 1);

		$data['audioImage'] = $this->unit->run($ajax_audioImage, "http://localhost/SOEN390_ATeam/uploads/1/1/1.jpg", "audioImage Function Test", "Tests the audioImage function with narrative 1. Passes if there is a narrative 1 in the uploads folder with the associated xml file");

		$ajax_audioImage2 = $this->ajax->audioImage(2, 2);

		$data['audioImage2'] = $this->unit->run($ajax_audioImage2, "http://localhost/SOEN390_ATeam/uploads/2/2/2.jpg", "audioImage Function Test", "Tests the audioImage function with narrative 2 which doesn't exist. Expected to fail");

		$this->view_wrapper('pages/ajax_test_report',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
