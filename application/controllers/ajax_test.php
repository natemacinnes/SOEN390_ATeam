<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Test the Ajax controller.
 */
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

		// Controllers render page output; we'll use ob_start() to buffer output and
		// capture printed data.
		$ajax = $this->load->controller('ajax');

		ob_start();
		$ajax->audioImage(1, 1);
		$ajax_audioImage = ob_get_contents();
		ob_end_clean();

		$data['audioImage'] = $this->unit->run($ajax_audioImage, base_url() . "uploads/1/1.jpg", "audioImage Function Test", "Tests the audioImage function with narrative 1. Passes if there is a narrative 1 in the uploads folder with the associated xml file");

		ob_start();
		$ajax->audioImage(3, 40);
		$ajax_audioImage2 = ob_get_contents();
		ob_end_clean();

		$data['audioImage2'] = $this->unit->run($ajax_audioImage2, base_url() . "uploads/3/5.jpg", "audioImage Function Test", "Tests the audioImage function with narrative 2 which doesn't exist. Expected to fail");

		return $this->unit->result();
	}
}
