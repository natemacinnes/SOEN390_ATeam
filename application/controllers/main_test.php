<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_Test extends YD_Controller
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
		$this->load->model('narrative_model');
		$narrative_get = $this->narrative_model->get(1);

		$narrative_get_array =  array(
			"narrative_id" => 1,
			"xml_path" => "uploads/1/1.xml",
			"audio_length" => 67,
			"created" => "2014-01-28 01:19:27",
			"uploaded" => "2014-01-28 01:19:37",
			"uploaded_by" => 1,
			"language" => "en",
			"views" => 23,
			"agrees" => 1,
			"disagrees" => 4,
			"shares" => 52,
			"flags" => 3
		);
		$data['narrative_get'] = $this->unit->run($narrative_get, $narrative_get_array, "Narrative Model Get Test", "This test is for the get function which will take in an ID of a narrative as input and will get all the record from the narratives table in the database. This test will pass if the array matches the hard-coded array.");
		$data['n_db_array'] = $narrative_get;  //array that's gotten from db
		$data['n_created_array'] = $narrative_get_array; // array that's hardcoded

		//get function with non-existing input
		$narr_get_non_ex = $this->narrative_model->get(999);
		$data['narr_get_non_ex'] = $this->unit->run($narr_get_non_ex, array(), "Narrative Model Get Test", "This test is for get with a non-existant input. Should return empty array.");


		//get_all() function
		$narr_get_all = $this->narrative_model->get_all();

		$narrative_get_all_array = array(
			0 => array(
				"narrative_id" => 6,
				"xml_path" => "uploads/5/2.xml",
				"audio_length" => 258,
				"created" => "2013-11-14 01:19:36",
				"uploaded" => "2014-01-28 01:19:41",
				"uploaded_by" => 2,
				"language" => "fr",
				"views" => 7,
				"agrees" => 18,
				"disagrees" => 1,
				"shares" => 3,
				"flags" => 65
			),

			1 => array(
				"narrative_id" => 5,
				"xml_path" => "uploads/5/1.xml",
				"audio_length" => 148,
				"created" => "2013-12-28 01:19:35",
				"uploaded" => "2014-01-28 01:19:40",
				"uploaded_by" => 1,
				"language" => "fr",
				"views" => 15,
				"agrees" => 2,
				"disagrees" => 4,
				"shares" => 9,
				"flags" => 6
			),

			2 => array(
				"narrative_id" => 4,
				"xml_path" => "uploads/4/2.xml",
				"audio_length" => 315,
				"created" => "2014-01-16 01:19:34",
				"uploaded" => "2014-01-28 01:19:39",
				"uploaded_by" => 2,
				"language" => "en",
				"views" => 5,
				"agrees" => 7,
				"disagrees" => 5,
				"shares" => 4,
				"flags" => 7
			),

			3 => array(
				"narrative_id" => 3,
				"xml_path" => "uploads/3/1.xml",
				"audio_length" => 635,
				"created" => "2014-01-23 01:19:34",
				"uploaded" => "2014-01-28 01:19:39",
				"uploaded_by" => 2,
				"language" => "en",
				"views" => 2,
				"agrees" => 4,
				"disagrees" => 4,
				"shares" => 16,
				"flags" => 4
			),

			4 => array(
				"narrative_id" => 2,
				"xml_path" => "uploads/2/2.xml",
				"audio_length" => 125,
				"created" => "2014-01-04 01:19:33",
				"uploaded" => "2014-01-28 01:19:38",
				"uploaded_by" => 1,
				"language" => "fr",
				"views" => 1,
				"agrees" => 2,
				"disagrees" => 2,
				"shares" => 2,
				"flags" => 16,
			),

			5 => array(
				"narrative_id" => 1,
				"xml_path" => "uploads/1/1.xml",
				"audio_length" => 67,
				"created" => "2014-01-28 01:19:27",
				"uploaded" => "2014-01-28 01:19:37",
				"uploaded_by" => 1,
				"language" => "en",
				"views" => 23,
				"agrees" => 1,
				"disagrees" => 4,
				"shares" => 52,
				"flags" => 3,
			),
		);
		$data['narrative_get_all'] = $this->unit->run($narr_get_all, $narrative_get_all_array, "Narrative Model Get All Test", "The get_all function takes in an optional sorting parameter and returns the full table entries for the narratives table. Test will make sure that it matches the hard-coded array and will succeed if it does.");
		$data['n_get_all_array'] = $narr_get_all;
		$data['n_all_created_arr'] = $narrative_get_all_array;



		//insert function

		$array_to_add = array(
			'narrative_id' => 7,
			'xml_path' => "uploads/1/1.xml",
			'audio_length' => 0,
			'created' => "2014-01-28 01:19:27",
			'uploaded' => "2014-01-28 01:19:28",
			'uploaded_by' => 1,
			'language' => "en",
			'views' => 0,
			'agrees' => 0,
			'disagrees' => 0,
			'shares' => 0,
			'flags' => 0
		);
		$this->narrative_model->insert($array_to_add);
		$narrative_insert_check = $this->narrative_model->get(7);

		$data['narrative_insert'] = $this->unit->run($narrative_insert_check, $array_to_add, "Narrative Model Insert Test", "This test (insert function) takes in an array to add to the narratives table in the database. It will check whether the array has been added by using the previously tested get function.");
		$data['n_insert_array'] = $array_to_add;
		$data['n_insert_get'] = $narrative_insert_check;


		//delete function
		$this->narrative_model->delete(array('narrative_id' => 7));
		$narrative_delete_check = $this->narrative_model->get(7);

		$data['narrative_delete'] = $this->unit->run($narrative_delete_check, null, "Narrative Model Delete Test", "This test (delete function) takes in an array of fields to find a specific row in the narratives table in the database and will delete that row. In this case we delete the previously inserted record and verify by the get function." );

		$this->view_wrapper('pages/test_report',$data);
	}

}
