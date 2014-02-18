<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

<<<<<<< HEAD:application/controllers/home.php
class Home extends YD_Controller
=======
/**
 * Used for providing static/view-only pages.
 */
class Pages extends YD_Controller
>>>>>>> 162f47074e073c03185dd35993e73de914aad96d:application/controllers/pages.php
{
	/**
	 * Constructor: initialize required libraries.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
   * The default method called, if none is provided.
   */
	public function index()
	{
		$this->view_wrapper('pages/home');
	}
<<<<<<< HEAD:application/controllers/home.php

	/*public function contact()
	{
		// Demo the system messages
		$this->system_message_model->set_message("This is a normal (notice) message");
		$this->system_message_model->set_message("This is a warning message", MESSAGE_WARNING);
		$this->system_message_model->set_message("This is an error message", MESSAGE_ERROR);
		// Map the $ip_address variable in the view to the value provided here.
		$data = array('ip_address' => $this->input->ip_address());
		// Render the views/pages/contact.php file using including the header/footer
		$this->view_wrapper('pages/contact', $data);
	}*/
=======
>>>>>>> 162f47074e073c03185dd35993e73de914aad96d:application/controllers/pages.php
}
