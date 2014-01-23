<?php
class MY_Controller extends CI_Controller
{
   function __construct()
   {
      parent::__construct();
	  $this->load->helper('form');
   }

   /**
   * Load the specified View, automatically wrapping it between the site's
   * header and footer.
   */
  public function view_wrapper($template, $data = array()) {
    $this->load->view('include/header', $data);
    $this->load->view('include/navigation', $data);
    $this->load->view($template, $data);
    $this->load->view('include/footer');
  }
}
