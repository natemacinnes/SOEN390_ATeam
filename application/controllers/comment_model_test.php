<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model_Test extends YD_Controller
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
		$data['title'] = "Unit Tests";

		//narrative_model.php
		//get(narrative_id) function
		$this->load->model('comment_model');
		$comment_get_all = $this->comment_model->get_all(1);
		$data['comment_get_all'] = $comment_get_all;

		$comment_get_all_array =  array (
				0 => array
					(
						"comment_id" => 1,
						"narrative_id" => 1,
						"created" => "2014-01-28 01:58:15",
						"parent_comment" => "",
						"body" => "This one rules"
					),

				1 => array
					(
						"comment_id" => 2,
						"narrative_id" => 1,
						"created" => "2014-01-28 01:58:28",
						"parent_comment" => 1,
						"body" => "No it doesn't"
					)

			);
		$data['comment_unit'] = $this->unit->run($comment_get_all, $comment_get_all_array, "Comment Model Get_All Test", "Comment Model Get_All Test. Expected to pass.");
		//$data['n_db_array'] = $narrative_get;  //array that's gotten from db
		//$data['n_created_array'] = $narrative_get_array; // array that's hardcoded


		$this->view_wrapper('pages/comment_model_test_report',$data);
	}

}
