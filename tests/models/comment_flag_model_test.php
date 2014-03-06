<?php
/**
 * @group Model
 */
class Comment_Flag_Model_Test extends CIUnit_TestCase
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

		$this->CI->load->model('comment_flag_model');
	}

	/**
	 * Call native CI unit tests here.
	 */
	public function index() {
	}

	/**
	 * UT-0038
	 * @covers Comment_Flag_Model::get_by_comment_id
	 */
	public function test__get_by_comment_id__valid_id()
	{
		$comments = $this->CI->comment_flag_model->get_by_comment_id(7);
		$comments_flag_row = array(
								0 => array
									(
										"cflag_id" => 1,
										"comment_id" => 7,
										"description" => "rudeness!",
										"date_created" => "2014-02-24 17:01:29"
									)

							);

		$this->assertEquals($comments, $comments_flag_row);
	}
	
	/**
	 * UT-0039
	 * @covers Comment_Flag_Model::get_by_comment_id
	 */
	public function test__get_by_comment_id__invalid_id()
	{
		$comments = $this->CI->comment_flag_model->get_by_comment_id(-1);

		$this->assertEquals($comments, array());
	}
	
	/**
	 * UT-0040
	 * @covers Comment_Flag_Model::insert
	 */
	 
	 public function test__insert__successful_insert()
	 {
		$this->CI->comment_flag_model->insert(15, 'test insert description');
		
		$inserted = false;
		
		$comments = $this->CI->comment_flag_model->get_by_comment_id(15);
		
		foreach($comments as $comment)
		{
			if(is_array($comment) && isset($comment['description']) && $comment['description'] == "test insert description")
			{
				$inserted = true;
			}
		}
		
		$this->assertEquals(TRUE, $inserted);
		
	 }

}
