<?php
/**
 * @group Model
 */
class Comment_Model_Test extends CIUnit_TestCase
{
	public function __contruct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Setup PHPUnit & load any required dependencies
	 */
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();

		$this->CI->load->model('comment_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0027
	 * @covers Comment_Model::get_all
	 */
	public function test__get_all__multiple_returned()
	{
		$comments = $this->CI->comment_model->get_all();

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

		$this->assertEquals(TRUE, $count > 1 && $array_only);
	}

}
