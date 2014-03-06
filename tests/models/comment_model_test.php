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
	

	/**
	 * UT-0045
	 * @covers Comment_Model::get
	 */
	 
	 public function test__get__valid_id()
	 {
		$comment = $this->CI->comment_model->get(1);
		$comment_arr = array
							(
								"comment_id" => 1,
								"narrative_id" => 59,
								"created" => "2014-02-23 14:30:44",
								"parent_comment" => '',
								"body" => "Im%20First%20-%20Tim%20was%20here",
								"status" => 1
							);
		
		$this->assertEquals($comment, $comment_arr);
	 }
	 
	/**
	 * UT-0046
	 * @covers Comment_Model::get
	 */
	 
	 public function test__get__invalid_id()
	 {
		$comment = $this->CI->comment_model->get(-1);
		
		$this->assertEquals($comment, array());
	 }

	 /**
	 * UT-0047
	 * @covers Comment_Model::get_by_narrative_id
	 */
	 
	 public function test__get_by_narrative_id__valid_id()
	 {
		$retrieved_comments = $this->CI->comment_model->get_by_narrative_id(33);
		
		$expected_comments_arr = array
									(
										7 => array
											(
												"comment_id" => 7,
												"narrative_id" => 33,
												"created" => "2014-02-24 16:51:24",
												"parent_comment" => "",
												"body" => "Test",
												"status" => 1
											)

									);
		
		$this->assertEquals($retrieved_comments, $expected_comments_arr);
	 }
	 
	 /**
	 * UT-0048
	 * @covers Comment_Model::get_by_narrative_id
	 */	 
	 
	 public function test__get_by_narrative_id__invalid_id()
	 {
		$retrieved_comments = $this->CI->comment_model->get_by_narrative_id(-1);
		
		$this->assertEquals($retrieved_comments, array());
	 }
}
