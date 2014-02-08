<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_Message_Model_Test extends MY_Controller {
  /**
   * Constructor: initialize required libraries.
   */
  public function __construct() {
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


  public function index() {
    $data['title'] = "Unit Tests";


    //system message model
    //testing set function
    $this->load->model('system_message_model');
    $system_message_set = $this->system_message_model->set_message("Hello");


    $data['system_message_model_set'] = $this->unit->run($_SESSION['system_messages'][MESSAGE_NOTICE][0], "Hello", "System Message Model Set Test","tests the system_message_model set function, passes is $_SESSION is set to our string, fails if not");



    //system message model
    //testing get function
    $system_message_get = $this->system_message_model->get_messages(TRUE);


    $data['system_message_model_get'] = $this->unit->run($system_message_get[MESSAGE_NOTICE][0], "Hello", "System Message Model Get Test", "tests the get function in system_message_model, it passes if the function gets the right $_SESSION, fails otherwise");





    $data['system_message_model_get_cleararray_string'] = $this->unit->run($_SESSION['system_messages'][MESSAGE_NOTICE][0], "Hello", "System Message Model Get Test", "tests the get function in system_message_model, it fails if the array is cleared");
    $data['system_message_model_get_cleararray_null'] = $this->unit->run($_SESSION['system_messages'][MESSAGE_NOTICE][0], "", "System Message Model Get Test", "tests the get function in system_message_model, it fails if the array is cleared");


    $this->view_wrapper('pages/system_message_test_report',$data);
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
