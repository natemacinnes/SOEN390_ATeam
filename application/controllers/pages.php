<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends YD_Controller
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
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
		$this->view_wrapper('pages/index');
	}

	public function contact()
	{
		// Demo the system messages
		$this->system_message_model->set_message("This is a normal (notice) message");
		$this->system_message_model->set_message("This is a warning message", MESSAGE_WARNING);
		$this->system_message_model->set_message("This is an error message", MESSAGE_ERROR);
		// Map the $ip_address variable in the view to the value provided here.
		$data = array('ip_address' => $this->input->ip_address());
		// Render the views/pages/contact.php file using including the header/footer
		$this->view_wrapper('pages/contact', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
