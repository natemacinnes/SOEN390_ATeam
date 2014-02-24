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
		$this->load->model('comment_model');
	}

	public function test__get_all__multiple_returned()
	{
		$comments = $this->comment_model->get_all();

		$count = 0;
		$array_only = TRUE;
		foreach($comments as $comment) {
			// Ensure we have an array of arrays with at least 2 items.
			if (is_array($comment) && isset($comment['comment_id'])) {
				$count++;
			}
			else {
				$array_only = FALSE;
				break;
			}
		}

		$this->unit->run(
			$count > 1 && $array_only,
			TRUE,
			"Comment retrieval: get all",
			"Retrieved data should be >= 2 comments."
		);
		return $this->unit->result();
	}

}
