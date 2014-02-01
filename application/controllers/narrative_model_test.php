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
	
 	
    $getallTest = $this->narrative_model->get_All("id");
 	
 	$data['sorting_id_fail'] = $this->unit->run($getallTest[0]['narrative_id'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  In the case of this test highest is 16, lowest is 0, Testing narrative idTest expected to fail"); 
 	$data['sorting_path_fail'] = $this->unit->run($getallTest[0]['xml_path'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing xml_path, In the case of this test it is null,  Test expected to fail"); 
 	$data['sorting_length_fail'] = $this->unit->run($getallTest[0]['audio_length'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing audio length value, In the case of this test it should be 0 (placeholder file), Test expected to fail"); 
 	$data['sorting_created_fail'] = $this->unit->run($getallTest[0]['created'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing created value, In the case of this test it should be 0 (placeholder file) Test expected to fail"); 
 	$data['sorting_uploaded_fail'] = $this->unit->run($getallTest[0]['uploaded'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing uploaded value, In the case of this test it should be 0 (placeholder file), Test expected to fail"); 
 	$data['sorting_uploadby_fail'] = $this->unit->run($getallTest[0]['uploaded_by'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing uploaded by value,  In the case of this test it should be 1, Test expected to fail"); 
 	$data['sorting_lang_fail'] = $this->unit->run($getallTest[0]['language'], "fr", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing language value, In the case of this test it should be en,  Test expected to fail"); 
 	$data['sorting_view_fail'] = $this->unit->run($getallTest[0]['views'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing views value, In the case of this test it should be 0, Test expected to fail"); 
 	$data['sorting_agree_fail'] = $this->unit->run($getallTest[0]['agrees'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing agrees value, In the case of this test it should be 0, Test expected to fail"); 
 	$data['sorting_disagree_fail'] = $this->unit->run($getallTest[0]['disagrees'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing disagrees value, In the case of this test it should be 0, Test expected to fail");
 	$data['sorting_shares_fail'] = $this->unit->run($getallTest[0]['shares'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing shares value, In the case of this test it should be 0, Test expected to fail");
 	$data['sorting_flags_fail'] = $this->unit->run($getallTest[0]['flags'], "-1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing flags value, In the case of this test it should be 0, Test expected to fail");
 	
 	$data['sorting_id_pass'] = $this->unit->run($getallTest[0]['narrative_id'], "16", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  In the case of this test highest is 16, lowest is 0, Test expected to pass"); 
 	$data['sorting_path_pass'] = $this->unit->run($getallTest[0]['xml_path'], null, "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest. Testing xml_path, In the case of this test it is null,  Test expected to pass"); 
 	$data['sorting_length_pass'] = $this->unit->run($getallTest[0]['audio_length'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing audio length value, In the case of this test it should be 0 (placeholder file), Test expected to pass"); 
 	$data['sorting_created_pass'] = $this->unit->run($getallTest[0]['created'], "0000-00-00 00:00:00", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing created value, In the case of this test it should be 0 (placeholder file) Test expected to pass"); 
 	$data['sorting_uploaded_pass'] = $this->unit->run($getallTest[0]['uploaded'], "0000-00-00 00:00:00", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing uploaded value, In the case of this test it should be 0 (placeholder file), Test expected to pass"); 
 	$data['sorting_uploadby_pass'] = $this->unit->run($getallTest[0]['uploaded_by'], "1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing uploaded by value,  In the case of this test it should be 1, Test expected to pass");
 	$data['sorting_lang_pass'] = $this->unit->run($getallTest[0]['language'], "en", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing language value, In the case of this test it should be en,  Test expected to pass"); 
 	$data['sorting_view_pass'] = $this->unit->run($getallTest[0]['views'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing views value, In the case of this test it should be 0, Test expected to pass"); 
 	$data['sorting_agree_pass'] = $this->unit->run($getallTest[0]['agrees'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing agrees value, In the case of this test it should be 0, Test expected to pass"); 
 	$data['sorting_disagree_pass'] = $this->unit->run($getallTest[0]['disagrees'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing disagrees value, In the case of this test it should be 0, Test expected to pass");
 	$data['sorting_shares_pass'] = $this->unit->run($getallTest[0]['shares'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing shares value, In the case of this test it should be 0, Test expected to pass");
 	$data['sorting_flags_pass'] = $this->unit->run($getallTest[0]['flags'], "0", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing flags value, In the case of this test it should be 0, Test expected to pass");
 	
 	 
 	$data['sorting_last'] = $this->unit->run($getallTest[15]['narrative_id'], "1", "Narrative Model get_all test","Tests get all function, which should sort the narratives in the database from highest narrative id to lowest.  Testing that the last file sorted has narrative_id of 1 (being the lowest value in database), Test expected to pass");
 	
 	
 	
 	$data['test']=$getallTest[0];
 	
 	
 	
 	
 	
 	$this->view_wrapper('pages/narrative_model_test_report',$data);
    
  }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
