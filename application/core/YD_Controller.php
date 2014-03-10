<?php
class YD_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		@session_start();
	}

	public static function set_instance(&$newCI)
	{
		// Ugly, but the only way to restore the static instance of CI_Controller
		// since PHP doesn't support proper inheritance of private static methods
		// and properties.
		$reflection = new ReflectionClass('CI_Controller');
		$reflectionProperty = $reflection->getProperty("instance");
		$reflectionProperty->setAccessible(TRUE);
		$reflectionProperty->setValue($newCI);
	}

	/**
	 * Returns the User ID of the logged in user, or NULL if not authenticated.
	 */
	protected function set_logged_in_user($admin_id)
	{
		return $this->session->set_userdata('user_id', $admin_id);
	}

	/**
	 * Returns the User ID of the logged in user, or NULL if not authenticated.
	 */
	protected function get_logged_in_user()
	{
		$admin_id = $this->session->userdata('user_id');
		return $this->admin_model->get($admin_id);
	}

	/**
	 * Ensures the user is logged in, and if not, redirect them to the login page.
	 *
	 * TODO: Redirect them to the page they requested after login.
	 */
	protected function require_login()
	{
		if (!$this->get_logged_in_user())
		{
			$this->system_message_model->set_message('You must log in to view this page.', MESSAGE_WARNING);
			redirect('admin/login');
		}
	}

	/**
	 * Load the specified View, automatically wrapping it between the site's
	 * header and footer.
	 */
	public function view_wrapper($template, $data = array(), $display_messages = TRUE)
	{
		$data['logged_in_user'] = $this->get_logged_in_user();
		$data['system_messages'] = array();
		if ($display_messages)
		{
			$data['system_messages'] = $this->system_message_model->get_messages();
			$data['validation_errors'] = validation_errors();
		}
		$this->load->view('include/header', $data);
		if ($data['logged_in_user'])
		{
			$this->load->view('include/navigation', $data);
		}
		$this->load->view('include/system_messages', $data);
		$this->load->view($template, $data);
		$this->load->view('include/footer', $data);
	}
}
