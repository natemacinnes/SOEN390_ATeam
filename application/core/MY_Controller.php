<?php
class MY_Controller extends CI_Controller {
<<<<<<< HEAD
   function __construct() {
      parent::__construct();
<<<<<<< HEAD
      // To store persistent data across a browsing session
      session_start();
=======
  function __construct() {
    parent::__construct();
    // To store persistent data across a browsing session
    session_start();
>>>>>>> ddce7130d114f7e7f914f6dc660173195c825bac

    // Common helpers
    $this->load->helper('url');
    $this->load->helper('form');

<<<<<<< HEAD
      // Session-based messaging
      $this->load->model('system_message_model');
=======
	  $this->load->helper('form');
>>>>>>> a82066656589d2533cc8b6591b99844898ee5a7b
   }
=======
    // Session-based messaging
    $this->load->model('system_message_model');
    $this->load->helper('form');
  }
>>>>>>> ddce7130d114f7e7f914f6dc660173195c825bac

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
