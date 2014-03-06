<?php
/**
 * @group Model
 */
class Comment_Flag_Model_Test extends CIUnit_TestCase
{
	/**
	 * Loads sample data into test tables.
	 * Key = table, value = fixture filename prefix.
	 */
	protected $tables = array(
		'admins' => 'admins',
		'narratives' => 'narratives',
		'comments' => 'comments',
    'comment_flags' => 'comment_flags',
  );

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
		$flags = $this->CI->comment_flag_model->get_by_comment_id(3);
		$this->assertEquals(2, count($flags));
		$this->assertEquals(1, $flags[0]['cflag_id']);
		$this->assertEquals(2, $flags[1]['cflag_id']);
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
		$comment_id = 2;
		$inserted_id = $this->CI->comment_flag_model->insert($comment_id, 'flag description');
		$this->assertEquals(4, $inserted_id);
	 }

}
