<?php
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		// To store persistent data across a browsing session
		//session_start();

		// Common helpers
		$this->load->helper('url');
		$this->load->helper('form');

			// Session-based messaging
			$this->load->model('system_message_model');
		$this->load->helper('form');
	 }

	/**
	 * Load the specified View, automatically wrapping it between the site's
	 * header and footer.
	 */
	public function view_wrapper($template, $data = array(), $display_messages = TRUE) {
		$data['system_messages'] = array();
		if ($display_messages) {
			$data['system_messages'] = $this->system_message_model->get_messages();
		}
		$this->load->view('include/header', $data);
		$this->load->view('include/navigation', $data);
		$this->load->view('include/system_messages', $data);
		$this->load->view($template, $data);
		$this->load->view('include/footer', $data);
	}
}
