<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narrative_Model_Test extends MY_Controller {
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
	$this->load->model('narrative_model');
	

	
	//testing narrative process with a path to a directory that does not exist
	$nar_model_nopath = $this->narrative_model->process_narrative("./uploads/tmp/1391131894");
	
	$data['narrativeModelMessage1'] = $this->unit->run($nar_model_nopath['error_message'], '', "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which does not exist, test to see if we get the desired error msg, test fails if error msg is not right (This test case is expected to fail");
	
 	$data['narrativeModelError1'] = $this->unit->run($nar_model_nopath['error'], 0, "Narrative Model Process Narrative test","Tests process narrative using a non existent tmp path, check to see if we get error = 1, fails if error != 1  (this test case is expected to fail");
	
	
	$data['narrativeModelMessage'] = $this->unit->run($nar_model_nopath['error_message'], 'Processing failed. Please attempt the upload again.', "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to pass");
	
 	$data['narrativeModelError'] = $this->unit->run($nar_model_nopath['error'], 1, "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to pass");
	
	//testing narrative process with a path that points to an existing directory
 	
 	$nar_model_goodpath = $this->narrative_model->process_narrative("./uploads/tmp/1391140978");
 	
 	$data['narrativeModelNoError1'] = $this->unit->run($nar_model_goodpath['error'], 1, "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to fail");
 	
 	$data['narrativeModelNoMessage1'] = $this->unit->run($nar_model_goodpath['error_message'], 'Processing failed. Please attempt the upload again.', "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to fail");
	
 	$data['narrativeModelNoError'] = $this->unit->run($nar_model_goodpath['error'], 0, "Narrative Model Process Narrative test","Tests process_narrative using a path to a tmp folder which  exists, test to see if we get no error msg, test fails if error msg is present (this test case is expected to pass");
 	
 	$data['narrativeModelNoMessage'] = $this->unit->run($nar_model_goodpath['error_message'], "", "Narrative Model Process Narrative test","Tests process narrative using a an existing tmp path, check to see if we get error = 0, fails if error != 0 (This test case is expected to pass");
	
 	
    $this->view_wrapper('pages/narrative_model_test_report',$data);
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
